<?php

namespace App\Models\UserManagement;

use Auth;
use App\Models\Client;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, LaratrustUserTrait, SoftDeletes;

    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['last_login'];

    protected static function boot()
    {
        parent::boot();

        if (!Auth::guest()) {
            static::addGlobalScope('platform', function(Builder $builder) {
                $builder->where('users.platform', platform());
            });

            if (platform() == 'Client') {
                static::addGlobalScope('client', function(Builder $builder) {
                    $builder->where('users.client_id', clientId())
                            ->whereHas('client');
                });
            }
        }

        static::addGlobalScope('active', function(Builder $builder) {
            $builder->where('users.active', 1);
        });
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
