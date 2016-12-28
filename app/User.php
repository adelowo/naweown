<?php

namespace Naweown;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const EMAIL_VALIDATED = 200;

    const EMAIL_UNVALIDATED = 100;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'moniker',
        'bio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function activateAccount()
    {
        $this->link()->delete();

        return $this->update(['is_email_activated' => self::EMAIL_VALIDATED]);
    }

    public function link()
    {
        return $this->hasOne(Link::class);
    }
}
