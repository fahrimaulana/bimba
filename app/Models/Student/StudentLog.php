<?php

namespace App\Models\Student;

use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use App\Models\Master\Department;
use App\Models\Master\MasterClass;
use App\Models\Master\MediaSource;
use App\Models\Master\StudentNote;
use App\Models\Master\StudentOutReason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Student\StudentScholarship;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLog extends Model
{
    use SoftDeletes;

     protected $dates = ["birth_date", "joined_date", "change_date", "out_date"];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
             $builder->where('student_logs.client_id', clientId());
        });

        static::addGlobalScope('trial', function(Builder $builder) {
             $builder->where('student_logs.status', 'Trial');
        });

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('student_logs.status', 'Active');
        });
    }

    public function getIsNewAttribute()
    {
        return $this->joined_date->month == month() && $this->joined_date->year == year();
    }

    public function getIndoStatusAttribute()
    {
        if ($this->status == 'Out') {
            $status = 'Keluar';
        } elseif ($this->isNew) {
            $status = 'Baru';
        } elseif ($this->status == 'Active') {
            $status = 'Aktif';
        }

        return $status;
    }

    public function getFeeAttribute()
    {
        $classPrice = ClassPrice::whereGradeId($this->grade_id)
            ->whereClassId($this->class_id)
            ->first();

        return $classPrice ? (int) $classPrice->price : 0;
    }

    public function trialTeacher()
    {
        return $this->belongsTo(Staff::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Staff::class);
    }

    public function mediaSource()
    {
        return $this->belongsTo(MediaSource::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function masterClass()
    {
        return $this->belongsTo(MasterClass::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function studentNote()
    {
        return $this->belongsTo(StudentNote::class);
    }

    public function outReason()
    {
        return $this->belongsTo(StudentOutReason::class);
    }

    public function activeScholarship()
    {
        return $this->belongsTo(StudentScholarship::class);
    }

}
