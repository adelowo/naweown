<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [
        'title',
        'slug',
        'images',
        'description'
    ];

    protected $casts = [
        'images' => 'array',
        'user_id' => 'int'
    ];
}
