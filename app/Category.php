<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug'
    ];

    protected $casts = [
      'number_of_views' => 'int'
    ];
}
