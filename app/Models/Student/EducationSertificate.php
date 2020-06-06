<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationSertificate extends Model
{
    use SoftDeletes;

    protected $dates = ['change_date'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('education_sertificates.client_id', clientId());
        });
    }
}
