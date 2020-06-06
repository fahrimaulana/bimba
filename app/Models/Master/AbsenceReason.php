<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsenceReason extends Model
{
    use SoftDeletes;

    protected $table = 'master_absence_reasons';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_absence_reasons.client_id', clientId());
        });
    }
}