<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::where('param', 'ref')->first();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ref' => 'required|url',
        ]);

        $data = $request->all();

        $settings = Setting::where('param', 'ref')->update([
            'value' => $data['ref']
        ]);

        return redirect()->route('admin.settings')->with('success', 'Настройки сохранены');
    }
}
