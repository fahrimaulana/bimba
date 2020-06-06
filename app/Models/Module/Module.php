<?php

namespace App\Models\Module;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('modules.client_id', clientId());
        });
    }
}
