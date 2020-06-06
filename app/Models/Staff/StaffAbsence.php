<?php

namespace App\Models\Staff;

use App\Models\Staff\Staff;
use App\Models\Master\AbsenceReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAbsence extends Model
{
    use SoftDeletes;

    protected $dates = ["absent_date"];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
             $builder->where('staff_absences.client_id', clientId());
        });
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function absenceReason()
    {
        return $this->belongsTo(AbsenceReason::class);
    }
}
