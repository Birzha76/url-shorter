<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Link;
use App\Models\Setting;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->is_admin) {
            $links = Link::with('user', 'domain')
                ->orderBy('created_at', 'DESC')
                ->paginate(20);
        }else {
            $links = Link::with('user', 'domain')
                ->where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'DESC')
                ->paginate(20);
        }

        return view('admin.links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $domains = Domain::all();
        return view('admin.links.create', compact('domains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'link_full' => 'nullable|url',
            'tiktok' => 'required',
            'country' => 'required',
        ]);

        $data = $request->all();

        if (!Auth::user()->is_admin) {
            $settings = Setting::where('param', 'ref')->first();
            $data['link_full'] = $settings->value;
        }

        $data['link_short'] = $this->generateShortLink();
        $data['user_id'] = Auth::user()->id;
        $link = Link::create($data);

        $linkResult = $link->domain->domain . '/' . $link->link_short;
        $linkResult = explode('http://', $linkResult)[1];

        return redirect()->route('admin.links.create')->with('link', $linkResult);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $domains = Domain::all();
        $link = Link::with('domain')->find($id);
        return view('admin.links.edit', compact('link', 'domains'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'link_full' => 'nullable|url',
            'tiktok' => 'required',
            'country' => 'required',
        ]);
        $link = Link::find($id);
        $link->update($request->all());

        return redirect()->route('admin.links.index')->with('success', 'Информация о ссылке обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $link = Link::find($id);
        $link->delete();

        $visits = Visit::where('link_id', $id)->delete();

        return redirect()->route('admin.links.index')->with('success', 'Ссылка удалена');
    }

    public function generateShortLink($length = 7)
    {
        $result = '';
        $arr = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '0',
        ];

        for ($i = 0; $i < $length; $i++) {
            $result .= $arr[random_int(0, count($arr) - 1)];
        }
        return $result;
    }

    public function go($domainId, $linkShort)
    {
        $data = [
            'domain_id' => $domainId,
            'link_short' => $linkShort,
        ];

        $validator = \Validator::make($data, [
            'domain_id' => 'required|numeric|exists:domains,id',
            'link_short' => 'required|exists:links,link_short',
        ]);

        if ($validator->fails()) {
            return abort(404);
        }

        $link = Link::where('domain_id', $data['domain_id'])
            ->where('link_short', $data['link_short'])
            ->first();

        $visit = Visit::create([
            'link_id' => $link->id,
        ]);

        return redirect()->away($link->link_full . '&click_id=' . $link->click_id, 301);
    }
}
