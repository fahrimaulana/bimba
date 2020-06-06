<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentNote extends Model
{
    use SoftDeletes;

    protected $table = 'master_student_notes';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_student_notes.client_id', clientId());
        });
    }
}