<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'user_id',
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFindByToken(Builder $builder, string $token)
    {
        return $builder->where('token', $token)->firstOrFail();
    }

    public function isExpired()
    {
        return carbon()
            ->diffInMinutes($this->getAttribute('created_at'))
        >=
        config('auth.token.expires_after');
    }
}
