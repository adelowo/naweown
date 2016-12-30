<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
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
