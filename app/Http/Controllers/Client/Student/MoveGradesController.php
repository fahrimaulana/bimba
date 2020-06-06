<?php

namespace App\Http\Controllers\Client\Student;;

use DB, Exception, Datatables;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Models\Master\MasterClass;
use App\Models\Master\MediaSource;
use App\Models\Master\StudentNote;
use App\Models\Master\StudentPhase;
use App\Models\Master\ClassPrice;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentClassLog;


class MoveGradesController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-move-grades');

        $students = Student::all();
        $classes = MasterClass::all();
        $grades = Grade::all();
        $classLogs = StudentClassLog::selectRaw("
                        students.nim,
                        students.`name` AS student_name,
                        master_departments.`name` AS department_name,
                        master_departments.`code` AS department_code,
                        (SELECT master_classes.code FROM master_classes WHERE master_classes.id = student_class_logs.old_class_id limit 1) as old_class_code,
                        (SELECT master_classes.code FROM master_classes WHERE master_classes.id = student_class_logs.new_class_id limit 1) as new_class_code,
                        (SELECT master_grades.name FROM master_grades WHERE master_grades.id = student_class_logs.old_grade_id LIMIT 1) AS old_grade_name,
                        (SELECT master_grades.name FROM master_grades WHERE master_grades.id = student_class_logs.new_grade_id LIMIT 1) AS new_grade_name,
                        student_class_logs.old_price,
                        student_class_logs.new_price,
                        student_class_logs.note
                    ")->leftJoin('students', 'student_class_logs.student_id', '=', 'students.id')
                    ->leftJoin('master_departments', 'students.department_id', '=', 'master_departments.id')
                    ->where(DB::raw('year(student_class_logs.created_at)'), year())
                    ->where(DB::raw('month(student_class_logs.created_at)'), month())
                    ->get();

        return view('client.student.move-grades.index', compact('students', 'classes', 'grades', 'classLogs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'required|integer|exists:students,id,deleted_at,NULL,status,Active',
            'class_id' => 'required|integer|exists:master_classes,id,deleted_at,NULL',
            'grade_id' => 'required|integer|exists:master_grades,id,deleted_at,NULL',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::withoutGlobalScope('active')->where('status', '!=', 'Trial')->findOrFail($request->student_id);

            $studentPrice = ClassPrice::where('class_id', $request->class_id)->where('grade_id', $request->grade_id)->first();



            $student->class_id = $request->class_id;
            $student->grade_id = $request->grade_id;
            $student->save();

            $studentClassLog = new StudentClassLog;
            $studentClassLog->student_id = $request->student_id;
            $studentClassLog->client_id = ClientId();
            $studentClassLog->old_class_id = $request->old_class_id;
            $studentClassLog->new_class_id = $request->class_id;
            $studentClassLog->old_grade_id = $request->old_grade_id;
            $studentClassLog->new_grade_id = $request->grade_id;
            $studentClassLog->note = $request->note;
            $studentClassLog->old_price = $request->old_student_fee;
            $studentClassLog->new_price = optional($studentPrice)->price;
            $studentClassLog->save();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        }
        DB::commit();

        return redirect()->route('client.student.move-grades.index')->with('notif_success', 'Golongan Berhasil di ubah');
    }

     public function update(Request $request, $id)
    {


        $this->validate($request, [

            'class_id' => 'required|integer|exists:master_classes,id,deleted_at,NULL',
            'grade_id' => 'required|integer|exists:master_grades,id,deleted_at,NULL',
            'note' => 'nullable|exists:master_student_notes,id,deleted_at,NULL'
        ]);

        $class = MasterClass::find($request->class_id);
        $student = Student::withoutGlobalScope('active')->where('status', '!=', 'Trial')->findOrFail($id);
        $student->class_id = $request->class_id;
        $student->grade_id = $request->grade_id;
        $student->note = $request->note;
        $student->save();

        return redirect()->route('client.student.move-grades.index')->with('notif_success', 'Pindah Gol telah berhasil diubah!');
    }


}
