<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentOutReason extends Model
{
    use SoftDeletes;

    protected $table = 'master_student_out_reasons';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_student_out_reasons.client_id', clientId());
        });
    }
}