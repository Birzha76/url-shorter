<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'link_id',
    ];

    public function link()
    {
        return $this->hasOne(Link::class, 'id', 'link_id');
    }
}
