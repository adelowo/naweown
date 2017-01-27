<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [
        'title',
        'slug',
        'images',
        'description',
        'cats'
    ];

    protected $casts = [
        'images' => 'array',
        'user_id' => 'int',
        'number_of_views' => 'int'
    ];

    protected $hidden = [
      'number_of_views'
    ];
}
