<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Link;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function postBack()
    {
        $click_id = \Request::query('clickid');
        $revenue = \Request::query('revenue');

        if (!empty($click_id) && !empty($revenue)) {
            $check = Link::where('click_id', $click_id)->first();
            if (!empty($check)) {
                $lead = Lead::create([
                    'link_id' => $check->id,
                    'click_id' => $click_id,
                    'revenue' => $revenue,
                ]);

                return 'Ok';
            }

            return 'ClickID not found.';
        }

        return 'Empty response';
    }
}
