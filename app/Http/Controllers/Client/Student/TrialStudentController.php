<?php

namespace App\Http\Controllers\Client\Student;

use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Master\Department;
use App\Models\Master\MediaSource;
use App\Models\Student\StudentLog;
use App\Http\Controllers\Controller;
use App\Models\Student\TrialStudent;
use App\Services\Student\LogStudent;
use Datatables, DB, Exception, CSV, Excel;

class TrialStudentController extends Controller
{
    private $delimiter = ',';

    public function index()
    {
        checkPermissionTo('view-trial-student-list');

        $staff = Staff::all();
        $mediaSources = MediaSource::all();
        $departments = Department::all();

        return view('client.student.trial-student.index', compact('staff', 'mediaSources', 'departments'));
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

    public function importCsv(Request $request)
    {
        $masterPath = storage_path('app/uploads/trial-student/');
        $uncleanCsv = fopen($masterPath . 'fail/import-trial-student-fail.csv', 'w+');
        $pathToCsv = $request->file('csv_file')->getPathName();

        Excel::load($pathToCsv, function($results) use($uncleanCsv) {
            $results->sheet(0, function($sheet) use($uncleanCsv) {

                foreach ($sheet->toArray() as $row) {
                    if ($row[0] == 'TGL MULAI' && $row[1] == 'KELAS' && $row[2] == 'NAMA' && $row[3] == 'TGL LAHIR') continue;
                    if ($row[0] == 'Tanggal Mulai :  Hari/Bulan/tahun = 07/10/2019' && $row[1] == 'Masukan Kelas lihat di Master Department' && $row[2] == 'Masukan nama murid' && $row[3] == 'Tanggal Lahir :  Hari/Bulan/tahun = 07/10/2019' && $row[4] == 'Masukan Guru Trial, lihat di Staff') continue;
                    if (empty($row[3])) {
                        $this->insertTo($uncleanCsv, $row, 'Tanggal Lahir tidak boleh kosong');
                        continue;
                    }

                    if (empty($row[0])) {
                        $this->insertTo($uncleanCsv, $row, 'Tanggal Mulai tidak boleh kosong');
                        continue;
                    }

                    $name = $row[2];

                    $birthDate = Carbon::createFromFormat('d/m/Y', $row[3])->format('Y-m-d');
                    $joinDate = Carbon::createFromFormat('d/m/Y', $row[0])->format('Y-m-d');

                    $trialTeacherId = optional(Staff::whereName($row[4])->first())->id;
                    $departmentId = optional(Department::whereName($row[1])->first())->id;
                    $mediaSourceId = optional(MediaSource::whereName($row[5])->first())->id;
                    $parentName = $row[6];
                    $phone = $row[7];
                    $address = $row[8];

                    if (empty($name)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama tidak boleh kosong');
                        continue;
                    }


                    if (empty($trialTeacherId)) {
                        $this->insertTo($uncleanCsv, $row, 'Guru Trial tidak boleh kosong');
                        continue;
                    }

                    if (empty($departmentId)) {
                        $this->insertTo($uncleanCsv, $row, 'Kelas tidak boleh kosong');
                        continue;
                    }

                    if (empty($mediaSourceId)) {
                        $this->insertTo($uncleanCsv, $row, 'Info tidak boleh kosong');
                        continue;
                    }

                    if (empty($parentName)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama Orang tidak boleh kosong');
                        continue;
                    }

                    if (empty($phone)) {
                        $this->insertTo($uncleanCsv, $row, 'NO TELP/HP tidak boleh kosong');
                        continue;
                    }

                    if (empty($address)) {
                        $this->insertTo($uncleanCsv, $row, 'Alamat tidak boleh kosong');
                        continue;
                    }

                    $trialStudent = ($row[9] == 'BARU') ? new TrialStudent : TrialStudent::withoutGlobalScopes()->where('name', $row[2])->where('birth_date', $birthDate)->whereNull('nim')->first();

                    if ($row[9] == 'BARU') {
                        $trialStudent->client_id             = clientId();
                    }

                    if (empty($trialStudent)) {
                        $this->insertTo($uncleanCsv, $row, 'Student tidak ditemukan atau tambahkan status BARU');
                        continue;
                    }

                    $trialStudent->name             = $name;
                    $trialStudent->birth_date       = $birthDate;
                    $trialStudent->joined_date      = $joinDate;
                    $trialStudent->trial_teacher_id = $trialTeacherId;
                    $trialStudent->department_id    = $departmentId;
                    $trialStudent->media_source_id  = $mediaSourceId;
                    $trialStudent->parent_name      = $parentName;
                    $trialStudent->phone            = $phone;
                    $trialStudent->address          = $address;
                    $trialStudent->status           = 'Trial';
                    $trialStudent->save();

                    $status = 'trial';
                    $log = new LogStudent($trialStudent, $status);
                    $log->destroy();
                    $log->process();
                }
            });
        });

        return redirect()->route('client.student.trial-student.index')->with('notif_success', 'Murid Trial baru telah berhasil import!');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-trial-student');

        $this->validate($request, [
            'name'             => 'required',
            'birth_date'       => 'required|date_format:d-m-Y',
            'joined_date'      => 'required|date_format:d-m-Y',
            'trial_teacher_id' => 'required|integer|exists:staff,id,deleted_at,NULL',
            'department_id'    => 'required|integer|exists:master_departments,id,deleted_at,NULL',
            'media_source_id'  => 'required|integer|exists:master_media_sources,id,deleted_at,NULL',
            'parent_name'      => 'required',
            'phone'            => 'required',
            'address'          => 'required'
        ]);

        $trialStudent                   = new TrialStudent;
        $trialStudent->client_id        = clientId();
        $trialStudent->name             = $request->name;
        $trialStudent->birth_date       = Carbon::parse($request->birth_date);
        $trialStudent->joined_date      = Carbon::parse($request->joined_date);
        $trialStudent->trial_teacher_id = $request->trial_teacher_id;
        $trialStudent->department_id    = $request->department_id;
        $trialStudent->media_source_id  = $request->media_source_id;
        $trialStudent->parent_name      = $request->parent_name;
        $trialStudent->phone            = $request->phone;
        $trialStudent->address          = $request->address;
        $trialStudent->status           = 'Trial';
        $trialStudent->save();

        $status = 'trial';
        $log = new LogStudent($trialStudent, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.trial-student.index')->with('notif_success', 'Murid trial telah berhasil ditambahkan!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-trial-student');

        $trialStudent = TrialStudent::findOrFail($id);
        $staff = Staff::all();
        $mediaSources = MediaSource::all();
        $departments = Department::all();

        return view('client.student.trial-student.edit', compact('trialStudent', 'staff', 'mediaSources', 'departments'));
    }

    public function show(Request $request, $id)
    {
        checkPermissionTo('view-trial-student');

        $show = true;
        $trialStudent = TrialStudent::findOrFail($id);
        $staff = Staff::all();
        $mediaSources = MediaSource::all();
        $departments = Department::all();

        return view('client.student.trial-student.edit', compact('show', 'trialStudent', 'staff', 'mediaSources', 'departments'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-trial-student');

        $this->validate($request, [
            'name'             => 'required',
            'birth_date'       => 'required|date_format:d-m-Y',
            'joined_date'      => 'required|date_format:d-m-Y',
            'trial_teacher_id' => 'required|integer|exists:staff,id,deleted_at,NULL',
            'department_id'    => 'required|integer|exists:master_departments,id,deleted_at,NULL',
            'media_source_id'  => 'required|integer|exists:master_media_sources,id,deleted_at,NULL',
            'parent_name'      => 'required',
            'phone'            => 'required',
            'address'          => 'required'
        ]);

        $trialStudent                   = TrialStudent::findOrFail($id);
        $trialStudent->name             = $request->name;
        $trialStudent->birth_date       = Carbon::parse($request->birth_date);
        $trialStudent->joined_date      = Carbon::parse($request->joined_date);
        $trialStudent->trial_teacher_id = $request->trial_teacher_id;
        $trialStudent->department_id    = $request->department_id;
        $trialStudent->media_source_id  = $request->media_source_id;
        $trialStudent->parent_name      = $request->parent_name;
        $trialStudent->phone            = $request->phone;
        $trialStudent->address          = $request->address;
        $trialStudent->save();

        $status = 'trial';
        $log = new LogStudent($trialStudent, $status);
        $log->destroy();
        $log->process();

        return redirect()->route('client.student.trial-student.index')->with('notif_success', 'Murid trial telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-trial-student');

        $trialStudent = TrialStudent::findOrFail($id);

        $status = 'trial';
        $log = new LogStudent($trialStudent, $status);
        $log->destroy();

        $trialStudent->delete();

        return redirect()->route('client.student.trial-student.index')->with('notif_success', 'Murid trial telah berhasil dihapus!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-trial-student-list');

        $trialStudents = StudentLog::withoutGlobalScopes(['active'])->
                        with([
                            'trialTeacher' => function($qry) { $qry->withoutGlobalScopes(); },
                            'department' => function($qry) { $qry->withoutGlobalScopes(); }
                        ])->select('student_logs.*')
                        ->where(DB::raw('year(student_logs.updated_at)'), year())
                        ->where(DB::raw('month(student_logs.updated_at)'), month());

        return Datatables::of($trialStudents)
                    ->addColumn('age', function($trialStudent) {
                        return $trialStudent->birth_date->diffInYears(now()) . ' Tahun';
                    })
                    ->editColumn('joined_date', function($trialStudent) {
                        return optional($trialStudent->joined_date)->format('d M Y');
                    })
                    ->editColumn('birth_date', function($trialStudent) {
                        return optional($trialStudent->birth_date)->format('d M Y');
                    })
                    ->addColumn('action', function($trialStudent) {
                        $addToMasterBook = '<a data-href="' . route('client.student.create', ['trial_id' => $trialStudent->student_id]) . '" data-toggle="modal" data-target="#confirm-add-to-master-book-modal" class="btn btn-sm btn-icon text-success tl-tip" data-toggle="tooltip" data-original-title="Tambah ke Buku Induk"><i class="icon wb-plus" aria-hidden="true"></i></a>';
                        $edit = '<a href="' . route('client.student.trial-student.edit', $trialStudent->student_id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah murid trial"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $show = '<a href="' . route('client.student.trial-student.show', $trialStudent->student_id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Lihat murid trial"><i class="icon wb-eye" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.student.trial-student.destroy', $trialStudent->student_id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus murid trial"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('view-trial-student') ? $show : '') . (userCan('add-to-master-book') ? $addToMasterBook : '') . (userCan('edit-trial-student') ? $edit : '') . (userCan('show-trial-student') ? $show : '') . (userCan('delete-trial-student') ? $delete : '');
                    })
                    ->make(true);
    }
}