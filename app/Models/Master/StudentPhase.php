<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPhase extends Model
{
    use SoftDeletes;

    protected $table = 'master_student_phases';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_student_phases.client_id', clientId());
        });
    }
}