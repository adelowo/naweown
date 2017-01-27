<?php

namespace Naweown;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'bio',
        'is_email_validated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'is_email_validated'
    ];

    public function activateAccount()
    {
        $this->token()->delete();

        return $this->update(
            ['is_email_validated' => self::EMAIL_VALIDATED]
        );
    }

    public function token()
    {
        return $this->hasOne(Token::class);
    }

    public function item()
    {
        return $this->hasMany(Item::class);
    }

    public function scopefindByEmailAddress(
        Builder $builder,
        string $emailAddress
    ) {
        return $builder->where('email', $emailAddress)
            ->firstOrFail();
    }

    public function isAccountActivated()
    {
        return
            (int)$this->getAttribute('is_email_validated')
            ===
            self::EMAIL_VALIDATED;
    }

    public function scopeFindByMoniker(
        Builder $builder,
        string $moniker
    ) {
        return $builder->where('moniker', $moniker)
            ->firstOrFail();
    }

    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    public function follows()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function updateProfile(array $values)
    {
        return $this->update($values);
    }

    public function hasApiToken()
    {
        return $this->getAttribute('api_token') !== null;
    }

    public function scopeFindByApiToken(
        Builder $builder,
        string $apiToken
    )
    {
        return $builder->where('api_token', $apiToken)
            ->firstOrFail();
    }
}
