<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'role_id',
        'authable_id',
        'authable_type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function authable()
    {
        return $this->morphTo();
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function getRole()
    {
        return $this->role->name;
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) { {
                foreach ($roles as $role) {
                    if ($this->checkIfUserHasRole($role)) {
                        return true;
                    }
                }
            }
        } else {
            return $this->checkIfUserHasRole($roles);
        }

        return false;
    }

    private function checkIfUserHasRole($role)
    {
        if ($this->role->name == $role) {
            return true;
        }
        return false;
    }
}
