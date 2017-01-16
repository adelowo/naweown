<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{

    protected $fillable = [
        'user_id',
        'follower_id'
    ];
}
