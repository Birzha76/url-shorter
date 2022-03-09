<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lead extends Model
{
    protected $fillable = [
        'link_id',
        'click_id',
        'revenue',
    ];

    public function link()
    {
        return $this->hasOne(Link::class, 'id', 'link_id');
    }

    public function getRevenueAttribute($value)
    {
        if (empty(Auth::user()->percent)) {
            return $value;
        }else {
            return $value / 100 * Auth::user()->percent;
        }
    }
}
