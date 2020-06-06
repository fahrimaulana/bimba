<?php

namespace App\Models\UserManagement;

use Laratrust\LaratrustPermission;
use Illuminate\Database\Eloquent\Builder;

class Permission extends LaratrustPermission
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('scope', function(Builder $builder) {
            $builder->where('permissions.scope', platform());
        });
    }
}
