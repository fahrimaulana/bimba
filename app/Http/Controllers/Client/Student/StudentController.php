<?php

namespace App\Http\Controllers\Client\Student;

use Carbon\Carbon;
use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Models\Master\MasterClass;
use App\Models\Master\MediaSource;
use App\Models\Master\StudentNote;
use App\Models\Student\StudentLog;
use App\Models\Master\StudentPhase;
use App\Http\Controllers\Controller;
use App\Models\Student\TrialStudent;
use App\Services\Student\LogStudent;
use App\Models\Master\StudentOutReason;
use App\Models\Student\StudentScholarship;
use Datatables, DB, Exception, CSV, Excel, Validator;

class StudentController extends Controller
{
    private $delimiter = ',';

    public function index()
    {
        $studentOutReasons = StudentOutReason::all();

        return view('client.student.student.index', compact('studentOutReasons'));
    }

    public function insertTo($csv, array $rows, $uncleanError = null)
    {
        if ($uncleanError) {
            fputs($csv, implode($rows, $this->delimiter) . $this->delimiter . $uncleanError . "\n");
        } else {
            fputs($csv, implode($rows, $this->delimiter) . "\n");
        }

        return true;
    }

    public function create(Request $request)
    {
        checkPermissionTo("create-student");

        $trialStudent = $request->trial_id ? TrialStudent::findOrFail($request->trial_id) : null;
        $phases = StudentPhase::all();
        $departments = Department::all();
        $classes = MasterClass::all();
        $grades = Grade::all();
        $staff = Staff::orderBy('name', 'ASC')->get();
        $mediaSources = MediaSource::all();
        $studentNotes = StudentNote::all();

        return view('client.student.student.create', compact('trialStudent', 'phases', 'departments', 'classes', 'grades', 'staff', 'mediaSources', 'studentNotes'));
    }

    public function importCsv(Request $request)
    {

        $masterPath = storage_path('app/uploads/student/');
        $uncleanCsv = fopen($masterPath . 'fail/import-student-fail.csv', 'w+');
        $pathToCsv = $request->file('csv_file')->getPathName();

        Excel::load($pathToCsv, function($results) use($uncleanCsv) {
            $results->sheet(0, function($sheet) use($uncleanCsv) {
               foreach ($sheet->toArray() as $row) {
                    if ($row[0] == 'NIM' && $row[1] == 'NAMA' && $row[2] == 'TMPT LAHIR' && $row[3] == 'TGL LAHIR') continue;
                    if ($row[0] == 'Masukan nim jika bukan murid baru' && $row[1] == 'masukan nama' && $row[2] == 'masukan tempat lahir' && $row[3] == 'Tanggal Lahir :  Hari/bulan/tahun = 25/03/2013' && $row[4] == 'Tanggal Masuk :  Hari/bulan/tahun = 25/03/2019' && $row[5] == 'Masukan Tahap lihat di master Phase Student :') continue;

                    $name = $row[1];
                    $birthPlace = $row[2];
                    $birthDate = Carbon::createFromFormat('d/m/Y', $row[3])->format('Y-m-d');
                    $joinedDate = Carbon::createFromFormat('d/m/Y', $row[4])->format('Y-m-d');
                    $phaseId = optional(StudentPhase::whereName($row[5])->first())->id;
                    $departmentId = optional(Department::whereName($row[8])->first())->id;
                    $classId = optional(MasterClass::whereCode($row[9])->first())->id;
                    $gradeId = optional(Grade::whereName($row[10])->first())->id;
                    $trialTeacherId = optional(Staff::whereName($row[12])->first())->id;
                    $teacherId = optional(Staff::whereName($row[13])->first())->id;
                    $parentName = $row[14];
                    $phone = $row[15];
                    $noteId = optional(StudentNote::whereName($row[16])->first())->id;
                    $mediaSourceId = optional(MediaSource::whereName($row[17])->first())->id;
                    $address = $row[18];
                    $status = $row[11];

                    if (empty($name)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama tidak boleh kosong');
                        continue;
                    }

                    if (empty($birthPlace)) {
                        $this->insertTo($uncleanCsv, $row, 'Tempat Lahir tidak boleh kosong');
                        continue;
                    }

                    if (empty($birthDate)) {
                        $this->insertTo($uncleanCsv, $row, 'Tanggal Lahir tidak boleh kosong');
                        continue;
                    }

                    if (empty($joinedDate)) {
                        $this->insertTo($uncleanCsv, $row, 'Tanggal Mulai tidak boleh kosong');
                        continue;
                    }

                    if (empty($phaseId)) {
                        $this->insertTo($uncleanCsv, $row, 'Tahap tidak boleh kosong');
                        continue;
                    }

                    if (empty($departmentId)) {
                        $this->insertTo($uncleanCsv, $row, 'Kelas tidak boleh kosong');
                        continue;
                    }

                    if (empty($classId)) {
                        $this->insertTo($uncleanCsv, $row, 'GOL tidak boleh kosong');
                        continue;
                    }

                    if (empty($gradeId)) {
                        $this->insertTo($uncleanCsv, $row, 'KD tidak boleh kosong');
                        continue;
                    }

                    if (empty($teacherId)) {
                        $this->insertTo($uncleanCsv, $row, 'Guru tidak boleh kosong');
                        continue;
                    }

                    if (empty($parentName)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama Orang Tua tidak boleh kosong');
                        continue;
                    }

                    if (empty($phone)) {
                        $this->insertTo($uncleanCsv, $row, 'NO TELP/HP tidak boleh kosong');
                        continue;
                    }

                    if (empty($mediaSourceId)) {
                        $this->insertTo($uncleanCsv, $row, 'Info tidak boleh kosong');
                        continue;
                    }

                    if (empty($address)) {
                        $this->insertTo($uncleanCsv, $row, 'Alamat tidak boleh kosong');
                        continue;
                    }
                    $student                        = (empty($row[0])) ? new Student : Student::withoutGlobalScopes()->where('nim', $row[0])->first();
                    if (empty($row[0])) {
                        $student->client_id             = clientId();
                        $student->nim                   = generateNewNim();
                    }

                    if (empty($student)) {
                        $this->insertTo($uncleanCsv, $row, 'Nim tidak ditemukan');
                        continue;
                    }


                    $student->name                  = $name;
                    $student->birth_place           = $birthPlace;
                    $student->birth_date            = $birthDate;
                    $student->joined_date           = $joinedDate;
                    $student->phase_id              = $phaseId;
                    $student->department_id         = $departmentId;
                    $student->class_id              = $classId;
                    $student->grade_id              = $gradeId;
                    $student->trial_teacher_id      = $trialTeacherId;
                    $student->teacher_id            = $teacherId;
                    $student->parent_name           = $parentName;
                    $student->phone                 = $phone;
                    $student->note_id               = $noteId;
                    $student->media_source_id       = $mediaSourceId;
                    $student->address               = $address;
                    $student->status                = $status;
                    $student->save();

                    $status = 'active';
                    $log = new LogStudent($student, $status);
                    $log->destroy();
                    $log->process();
                }
            });
        });

        return redirect()->route('client.student.index')->with('notif_success', 'Murid baru telah berhasil import!');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-student');

        $this->validate($request, [
            'trial_student_id' => 'nullable|integer|exists:students,id,deleted_at,NULL,status,Trial',
            'nim' => 'nullable|min:4|max:10|unique:students,nim',
            'name' => 'required',
            'joined_date' => 'required|date_format:d-m-Y',
            'birth_place' => 'required',
            'birth_date' => 'required|date_format:d-m-Y',
            'phase_id' => 'required|integer|exists:master_student_phases,id,deleted_at,NULL',
            'class_id' => 'required|integer|exists:master_classes,id,deleted_at,NULL',
            'grade_id' => 'required|integer|exists:master_grades,id,deleted_at,NULL',
            'teacher_id' => 'required|integer|exists:staff,id,deleted_at,NULL',
            'trial_teacher_id' => 'nullable|integer|exists:staff,id,deleted_at,NULL',
            'department_id' => 'required|integer|exists:master_departments,id,deleted_at,NULL',
            'media_source_id' => 'required|integer|exists:master_media_sources,id,deleted_at,NULL',
            'parent_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'note_id' => 'nullable|integer|exists:master_student_notes,id,deleted_at,NULL'
        ]);

        $class = MasterClass::find($request->class_id);

        $student = $request->trial_student_id ? TrialStudent::findOrFail($request->trial_student_id) : new Student;
        $student->client_id = clientId();
        $student->nim = generateNewNim();
        $student->name = $request->name;
        $student->joined_date = Carbon::parse($request->joined_date);
        $student->birth_place = $request->birth_place;
        $student->birth_date = Carbon::parse($request->birth_date);
        $student->phase_id = $request->phase_id;
        $student->department_id = $request->department_id;
        $student->class_id = $request->class_id;
        $student->grade_id = $request->grade_id;
        $student->teacher_id = $request->teacher_id;
        $student->trial_teacher_id = $request->trial_teacher_id;
        $student->media_source_id = $request->media_source_id;
        $student->parent_name = $request->parent_name;
        $student->phone = $request->phone;
        $student->address = $request->address;
        $student->note_id = $request->note_id;
        $student->status = 'Active';
        $student->save();

        if ($class->scholarship !== "None") {
            $scholarship = new StudentScholarship;
            $scholarship->student_id = $student->id;
            $scholarship->start_date = Carbon::parse($request->joined_date);
            $scholarship->end_date = Carbon::parse($request->joined_date)->addMonths(6);
            $scholarship->period = 1;
            $scholarship->save();

            $student->active_scholarship_id = $scholarship->id;
            $student->save();
        }

        $status = 'active';
        $log = new LogStudent($student, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.index')->with('notif_success', 'Murid baru telah berhasil disimpan!');
    }

    public function show(Request $request, $id)
    {
        checkPermissionTo('view-student');

        $show = true;
        $student = Student::withoutGlobalScope('active')->where('status', '!=', 'Trial')->findOrFail($id);
        $phases = StudentPhase::all();
        $departments = Department::all();
        $classes = MasterClass::with('group')->get();
        $grades = Grade::all();
        $staff = Staff::all();
        $staff = Staff::all();
        $mediaSources = MediaSource::all();
        $studentNotes = StudentNote::all();

        return view('client.student.student.edit', compact('show', 'student', 'phases', 'departments', 'classes', 'grades', 'staff', 'staff', 'mediaSources', 'studentNotes'));
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-student');

        $student = Student::withoutGlobalScope('active')->where('status', '!=', 'Trial')->findOrFail($id);
        $phases = StudentPhase::all();
        $departments = Department::all();
        $classes = MasterClass::with('group')->get();
        $grades = Grade::all();
        $staff = Staff::all();
        $mediaSources = MediaSource::all();
        $studentNotes = StudentNote::all();

        return view('client.student.student.edit', compact('student', 'phases', 'departments', 'classes', 'grades', 'staff', 'staff', 'mediaSources', 'studentNotes'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-student');

        $this->validate($request, [
            'name' => 'required',
            'joined_date' => 'required|date_format:d-m-Y',
            'birth_place' => 'required',
            'birth_date' => 'required|date_format:d-m-Y',
            'phase_id' => 'required|integer|exists:master_student_phases,id,deleted_at,NULL',
            'class_id' => 'required|integer|exists:master_classes,id,deleted_at,NULL',
            'grade_id' => 'required|integer|exists:master_grades,id,deleted_at,NULL',
            'teacher_id' => 'required|integer|exists:staff,id,deleted_at,NULL',
            'trial_teacher_id' => 'nullable|integer|exists:staff,id,deleted_at,NULL',
            'department_id' => 'required|integer|exists:master_departments,id,deleted_at,NULL',
            'media_source_id' => 'required|integer|exists:master_media_sources,id,deleted_at,NULL',
            'parent_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'note_id' => 'nullable|integer|exists:master_student_notes,id,deleted_at,NULL'
        ]);

        $class = MasterClass::find($request->class_id);
        $student = Student::withoutGlobalScope('active')->where('status', '!=', 'Trial')->findOrFail($id);
        // $student->nim = $request->nim;
        $student->name = $request->name;
        $student->joined_date = Carbon::parse($request->joined_date);
        $student->birth_place = $request->birth_place;
        $student->birth_date = Carbon::parse($request->birth_date);
        $student->phase_id = $request->phase_id;
        $student->department_id = $request->department_id;
        $student->class_id = $request->class_id;
        $student->grade_id = $request->grade_id;
        $student->teacher_id = $request->teacher_id;
        $student->trial_teacher_id = $request->trial_teacher_id;
        $student->media_source_id = $request->media_source_id;
        $student->parent_name = $request->parent_name;
        $student->phone = $request->phone;
        $student->address = $request->address;
        $student->note_id = $request->note_id;
        $student->save();

        if ($class->scholarship !== "None") {
            $scholarship = new StudentScholarship;
            $scholarship->student_id = $student->id;
            $scholarship->start_date = Carbon::parse($request->joined_date);
            $scholarship->end_date = Carbon::parse($request->joined_date)->addMonths(6);
            $scholarship->period = 1;
            $scholarship->save();

            $student->active_scholarship_id = $scholarship->id;
            $student->save();
        }

        $status = 'active';
        $log = new LogStudent($student, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.index')->with('notif_success', 'Murid telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-student');

        $student = Student::withoutGlobalScope('active')->where('status', '!=', 'Trial')->findOrFail($id);

        $status = 'active';
        $log = new LogStudent($student, $status);
        $log->destroy();

        $student->delete();

        return redirect()->route('client.student.index')->with('notif_success', 'Murid telah berhasil dihapus!');
    }

    public function setAsOut(Request $request, $id)
    {
        checkPermissionTo('set-student-as-out');

        $student = Student::findOrFail($id);

        $this->validate($request, [
            'reason_id' => 'required|integer|exists:master_student_out_reasons,id,deleted_at,NULL'
        ]);

        $student->out_date = Carbon::parse($request->out_date)->format('Y-m-d');
        $student->out_reason_id = $request->reason_id;
        $student->status = 'Out';
        $student->save();

        $status = 'active';
        $log = new LogStudent($student, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.index')->with('notif_success', 'Murid telah berhasil diset sebagai keluar!');
    }

    public function setAsActive(Request $request, $id)
    {
        checkPermissionTo('set-student-as-out');

        $student = Student::withoutGlobalScope('active')
            ->whereStatus('Out')
            ->findOrFail($id);

        $student->out_date = null;
        $student->out_reason_id = null;
        $student->status = 'Active';
        $student->save();

        $status = 'out';
        $log = new LogStudent($student, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.index')->with('notif_success', 'Murid telah berhasil diset sebagai aktif');
    }

    public function extendScholarship(Request $request, $id)
    {
        checkPermissionTo('extend-scholarship');

        $this->validate($request, [
            'extended_date' => 'required|date_format:d-m-Y'
        ]);

        $student = Student::withoutGlobalScope('active')
            ->whereNotNull('active_scholarship_id')
            ->whereStatus('Active')
            ->findOrFail($id);

        $lastPeriod = optional($student->activeScholarship)->period;

        $scholarship = new StudentScholarship;
        $scholarship->student_id = $student->id;
        $scholarship->start_date = Carbon::parse($request->extended_date);
        $scholarship->end_date = $scholarship->start_date->addMonths(6);
        $scholarship->period = $lastPeriod + 1;
        $scholarship->save();

        $student->active_scholarship_id = $scholarship->id;
        $student->save();

        $status = 'active';
        $log = new LogStudent($student, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.index')->with('notif_success', 'Murid telah berhasil diperpanjang masa beasiswanya.');
    }

    public function getData()
    {
        checkPermissionTo('view-student-list');

        $students = StudentLog::withoutGlobalScopes(['trial', 'active'])
            ->with([
                'masterClass' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'department' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'grade' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'teacher' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'outReason' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'activeScholarship' => function ($qry) {
                    $qry->withoutGlobalScopes();
                }
            ])
            ->where(DB::raw('year(student_logs.updated_at)'), year())
            ->where(DB::raw('month(student_logs.updated_at)'), month())
            ->where('student_logs.status', '!=', 'Trial')
            ->select('student_logs.*');

        return Datatables::of($students)
            ->addColumn('age', function ($student) {
                return $student->birth_date->diffInYears(now()) . ' Thn';
            })
            ->addColumn('info', function ($student) {
                return
                    "<b>Kelas</b>: " . optional($student->department)->name . "<br>" .
                    "<b>Gol</b>: " . optional($student->masterClass)->name . "<br>" .
                    "<b>KD</b>: " . optional($student->grade)->name . "<br>" .
                    "<b>Guru</b>: " . optional($student->teacher)->name;
            })
            ->addColumn('scholarship', function ($student) {
                return $student->status == 'Active' && $student->activeScholarship ?
                    '<span class="tag tag-' . $student->activeScholarship->colorClass . ' tl-tip">' .
                    "<b>Status</b>: " . $student->activeScholarship->status . "<br>" .
                    "<b>Periode</b>: " . $student->activeScholarship->period . "<br>" .
                    "<b>Tgl Berakhir</b>: " . optional($student->activeScholarship->end_date)->format('d M\'y') .
                    '</span>' : '-';
            })
            ->editColumn('joined_date', function ($student) {
                $studyLength = yearMonthFormat(optional($student->joined_date));

                return optional($student->joined_date)->format('d M\'y') . "<br>({$studyLength})";
            })
            ->editColumn('status', function ($student) {
                if ($student->status == 'Out') {
                    // if (Carbon::parse($student->out_date)->format('m-Y') == Carbon::now()->format('m-Y'))
                   if ($student->out_date)
                    {
                        return '<span class="tag tag-danger tl-tip">Tgl Keluar<br>' . optional($student->out_date)->format('d M Y') . '<br><br>Alasan:<br>' . optional($student->outReason)->reason . '</span>';
                    } else {
                        return '<span class="tag tag-success tl-tip">Aktif</span>';
                    }
                } elseif ($student->isNew) {
                    return '<span class="tag tag-primary tl-tip">Baru</span>';
                } elseif ($student->status == 'Active') {
                    return '<span class="tag tag-success tl-tip">Aktif</span>';
                }
            })
            ->addColumn('action', function ($student) {
                $extendScholarship = $student->activeScholarship ? '<a data-href="' . route('client.student.scholarship.extend', $student->student_id) . '" data-toggle="modal" data-target="#confirm-extend-scholarship-modal" class="btn btn-sm btn-icon text-warning tl-tip" data-toggle="tooltip" data-original-title="Perpanjang beasiswa"><i class="icon wb-calendar" aria-hidden="true"></i></a>' : '';
                $setAsOut = $student->status == 'Active' ? '<a data-href="' . route('client.student.set.out', $student->student_id) . '" data-toggle="modal" data-target="#confirm-set-as-out-modal" class="btn btn-sm btn-icon text-danger tl-tip" data-toggle="tooltip" data-original-title="Set sebagai keluar"><i class="icon wb-close" aria-hidden="true"></i></a>' : '';
                $setAsActive = $student->status == 'Out' ? '<a data-href="' . route('client.student.set.active', $student->student_id) . '" data-toggle="modal" data-target="#confirm-set-as-active-modal" class="btn btn-sm btn-icon text-success tl-tip" data-toggle="tooltip" data-original-title="Set sebagai aktif"><i class="icon wb-check" aria-hidden="true"></i></a>' : '';
                $show = '<a href="' . route('client.student.show', $student->student_id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Lihat murid"><i class="icon wb-eye" aria-hidden="true"></i></a>';
                $edit = '<a href="' . route('client.student.edit', $student->student_id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah murid"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.student.destroy', $student->student_id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus murid"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('extend-scholarship') ? $extendScholarship : '') . (userCan('set-student-as-out') ? $setAsOut : '') . (userCan('set-student-as-active') ? $setAsActive : '') . (userCan('view-student') ? $show : '') . (userCan('edit-student') ? $edit : '') . (userCan('delete-student') ? $delete : '');
            })
            ->rawColumns(['joined_date', 'info', 'scholarship', 'status', 'action'])
            ->make(true);
    }
}
