<?php

namespace App\Models\UserManagement;

use App\Models\UserManagement\User;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        parent::withoutGlobalScopes(['platform', 'client']);

        static::addGlobalScope('platform', function(Builder $builder) {
            $builder->where('users.platform', 'Admin');
        });
    }
}
