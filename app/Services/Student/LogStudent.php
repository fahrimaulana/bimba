<?php
namespace App\Services\Student;

use DB;
use App\Models\Student\Student;
use App\Models\Student\StudentLog;

class LogStudent
{
    private $student;
    private $status;

    public function __construct($student, $status)
    {
        $this->student = Student::withoutGlobalScopes()->lockForUpdate()->find($student->id);
        $this->status = $status;
    }

    public function destroy()
    {
        $studentLogs = StudentLog::withoutGlobalScopes()
                        ->where('student_id', $this->student->id)
                        ->when($this->status == 'trial', function($qry) {
                            return $qry->whereStatus('Trial');
                        })
                        ->when($this->status == 'active', function($qry) {
                            return $qry->where('status', '!=', 'Trial');
                        })
                        ->when($this->status == 'out', function($qry) {
                            return $qry->whereStatus('Out');
                        })
                        ->where(DB::raw('year(student_logs.created_at)'), year())
                        ->where(DB::raw('month(student_logs.created_at)'), month())->get();

        foreach ($studentLogs as $data)
        {
            $data->delete();
        }

        return;
    }

    public function process()
    {

        $studentLog                   = new StudentLog;
        $studentLog->client_id        = $this->student->client_id;
        $studentLog->nim              = $this->student->nim;
        $studentLog->name             = $this->student->name;
        $studentLog->birth_place      = $this->student->birth_place;
        $studentLog->birth_date       = $this->student->birth_date;
        $studentLog->joined_date      = $this->student->joined_date;
        $studentLog->phase_id         = $this->student->phase_id;
        $studentLog->department_id    = $this->student->department_id;
        $studentLog->class_id         = $this->student->class_id;
        $studentLog->grade_id         = $this->student->grade_id;
        $studentLog->trial_teacher_id = $this->student->trial_teacher_id;
        $studentLog->teacher_id       = $this->student->teacher_id;
        $studentLog->parent_name      = $this->student->parent_name;
        $studentLog->phone            = $this->student->phone;
        $studentLog->note_id          = $this->student->note_id;
        $studentLog->media_source_id  = $this->student->media_source_id;
        $studentLog->address          = $this->student->address;
        $studentLog->status           = $this->student->status;
        $studentLog->student_id       = $this->student->id;
        $studentLog->out_date         = $this->student->out_date;
        $studentLog->out_reason_id    = $this->student->out_reason_id;
        $studentLog->save();

        return ;
    }
}