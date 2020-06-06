<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tuition extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('tuitions.client_id', clientId());
        });

        static::addGlobalScope('period', function (Builder $builder) {
            $builder->where('tuitions.year', year())
                ->where('tuitions.month', month());
        });
    }
}
