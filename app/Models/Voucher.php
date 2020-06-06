<?php

namespace App\Models;

use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('vouchers.client_id', clientId());
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function invitedStudent()
    {
        return $this->belongsTo(Student::class);
    }
}
