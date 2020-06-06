<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Builder;

class Client extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        parent::withoutGlobalScopes(['platform', 'client']);

        static::addGlobalScope('platform', function (Builder $builder) {
            $builder->where('users.platform', 'Client');
        });
    }
}
