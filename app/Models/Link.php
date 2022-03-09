<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use Sluggable;

    protected $fillable = [
        'user_id',
        'domain_id',
        'link_short',
        'link_full',
        'tiktok',
        'country',
    ];

    public function domain()
    {
        return $this->hasOne(Domain::class, 'id', 'domain_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault([
            'name' => 'Test',
        ]);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'link_id', 'id');
    }

    public function sluggable(): array
    {
        return [
            'click_id' => [
                'source' => 'link_short'
            ]
        ];
    }

    public function getTiktokAttribute($value)
    {
        return '@' . $value;
    }
}
