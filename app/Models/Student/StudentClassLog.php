<?php

namespace App\Models\Student;

use App\Models\Master\Grade;
use App\Models\Student\Student;
use App\Models\Master\MasterClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentClassLog extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('student_class_logs.client_id', clientId());
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function oldClass()
    {
        return $this->belongsTo(MasterClass::class, 'old_class_id');
    }

    public function newClass()
    {
        return $this->belongsTo(MasterClass::class, 'new_class_id');
    }

    public function oldGrade()
    {
        return $this->belongsTo(Grade::class, 'old_grade_id');
    }

    public function newGrade()
    {
        return $this->belongsTo(Grade::class, 'new_grade_id');
    }
}
