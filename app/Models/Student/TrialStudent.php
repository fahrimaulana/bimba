<?php

namespace App\Models\Student;

use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use App\Models\Master\Department;
use App\Models\Master\MasterClass;
use App\Models\Master\MediaSource;
use App\Models\Master\StudentNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialStudent extends Model
{
    use SoftDeletes;

    protected $table = 'students';
    protected $dates = ["birth_date", "joined_date"];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
             $builder->where('students.client_id', clientId());
        });

        static::addGlobalScope('trial', function(Builder $builder) {
             $builder->where('students.status', 'Trial');
        });
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
}