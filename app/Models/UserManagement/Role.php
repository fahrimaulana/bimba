<?php

namespace App\Models\UserManagement;

use App\Models\Client;
use Laratrust\LaratrustRole;
use Illuminate\Database\Eloquent\Builder;

class Role extends LaratrustRole
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('platform', function(Builder $builder) {
            $builder->where('roles.platform', platform());
        });

        if (platform() == 'Client') {
            static::addGlobalScope('client', function(Builder $builder) {
                $builder->where('roles.client_id', clientId())
                        ->whereHas('client');
            });
        }
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
