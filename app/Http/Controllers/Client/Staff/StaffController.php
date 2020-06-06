<?php

namespace App\Http\Controllers\Client\Staff;

use Datatables, DB, Exception, CSV, Excel, File;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;
use App\Models\Master\StaffPosition;

class StaffController extends Controller
{
    private $delimiter = ',';

    public function index()
    {
        checkPermissionTo('view-staff-list');

        $departments = Department::all();
        $staffPositions = StaffPosition::all();

        return view('client.staff.staff.index', compact('departments', 'staffPositions'));
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

        $masterPath = storage_path('app/uploads/staff/');
        $uncleanCsv = fopen($masterPath . 'fail/import-staff-fail.csv', 'w+');
        $pathToCsv = $request->file('csv_file')->getPathName();

        Excel::load($pathToCsv, function($results) use($uncleanCsv) {
            $results->sheet(0, function($sheet) use($uncleanCsv) {
                foreach ($sheet->toArray() as $row) {
                    if ($row[0] == 'NIK' && $row[1] == 'Nama' && $row[2] == 'Jabatan' && $row[3] == 'Status' && $row[4] == 'Departement' && $row[5] == 'Tanggal Masuk') continue;
                    if ($row[0] == 'Masukan Nik: 47510100000002' && $row[1] == 'Nama Staff : Fandy Kurniawan' && $row[2] == 'Masukan ID jabatan lihat di master Jabatan Staff : 6' && $row[3] == 'Masukan Status: Active,Intern,Resign') continue;
                    $nik = $row[0];
                    $staffNik = Staff::where('nik', $nik)->first();
                    $name = $row[1];
                    $staffPosition = StaffPosition::find($row[2]);
                    $status = $row[3];
                    $department = Department::find($row[4]);

                    $birthDate = Carbon::createFromFormat('d/m/Y', $row[6])->format('Y-m-d');
                    $joinedDate = Carbon::createFromFormat('d/m/Y', $row[5])->format('Y-m-d');
                    $phone = $row[7];
                    $email = $row[8];
                    $account_number = $row[9];
                    $account_bank = $row[10];
                    $account_name = $row[11];

                    if (empty($nik)) {
                        $this->insertTo($uncleanCsv, $row, 'Nik tidak boleh kosong');
                        continue;
                    }

                    if ($staffNik) {
                        $this->insertTo($uncleanCsv, $row, 'Nik tidak boleh ada yang sama');
                        continue;
                    }

                    if (empty($name)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama tidak boleh kosong');
                        continue;
                    }

                    if(empty($staffPosition)) {
                        $this->insertTo($uncleanCsv, $row, 'Id Jabatan tidak boleh kosong atau tidak di temukan di client '.Client()->name);
                        continue;
                    }

                    if (empty($status)) {
                        $this->insertTo($uncleanCsv, $row, 'Status tidak boleh kosong');
                        continue;
                    }

                    if (empty($department)) {
                        $this->insertTo($uncleanCsv, $row, 'Id Department ini tidak boleh kosong atau tidak di temukan di client '.Client()->name);
                        continue;
                    }

                    if (empty($joinedDate)) {
                        $this->insertTo($uncleanCsv, $row, 'TGL Masuk tidak boleh kosong');
                        continue;
                    }

                    if (empty($birthDate)) {
                        $this->insertTo($uncleanCsv, $row, 'TGL Lahir tidak boleh kosong');
                        continue;
                    }

                    if (empty($phone)) {
                        $this->insertTo($uncleanCsv, $row, 'No TELP/HP tidak boleh kosong atau tidak di temukan di client '.Client()->phone);
                        continue;
                    }

                    if (empty($email)) {
                        $this->insertTo($uncleanCsv, $row, 'Email tidak boleh kosong atau tidak di temukan di client '.Client()->email);
                        continue;
                    }

                    if (empty($account_number)) {
                        $this->insertTo($uncleanCsv, $row, 'No Rekening tidak boleh kosong atau tidak di temukan di client '.Client()->account_number);
                        continue;
                    }

                    if (empty($account_bank)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama Bank tidak boleh kosong atau tidak di temukan di client '.Client()->account_bank);
                        continue;
                    }

                    if (empty($account_name)) {
                        $this->insertTo($uncleanCsv, $row, 'Nama Akun Bank tidak boleh kosong atau tidak di temukan di client '.Client()->account_name);
                        continue;
                    }


                    $staff                 = new Staff;
                    $staff->client_id      = clientId();
                    $staff->nik            = $row[0];
                    $staff->name           = $row[1];
                    $staff->position_id    = $row[2];
                    $staff->joined_date    = $joinedDate;
                    $staff->birth_date     = $birthDate;
                    $staff->department_id  = $row[4];
                    $staff->status         = $row[3];
                    $staff->phone          = $row[7];
                    $staff->email          = $row[8];
                    $staff->account_number = $row[9];
                    $staff->account_bank   = $row[10];
                    $staff->account_name   = $row[11];
                    $staff->save();
                }
            });
        });

        return redirect()->route('client.staff.index')->with('notif_success', 'Staff baru telah berhasil import!');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-staff');

        $this->validate($request, [
            'nik'            => 'required|unique:staff,nik',
            'name'           => 'required',
            'joined_date'    => 'required|date_format:d-m-Y',
            'birth_date'     => 'required|date_format:d-m-Y',
            'position_id'    => 'required|integer|exists:master_staff_positions,id,deleted_at,NULL',
            'status'         => 'required|in:Active,Intern,Resign',
            'department_id'  => 'required|integer|exists:master_departments,id,deleted_at,NULL',
            'phone'          => 'required|min:11|numeric',
            'email'          => 'required|email',
            'account_number' => 'required',
            'account_bank'   => 'required',
            'account_name'   => 'required'
        ]);

        $staff                 = new Staff;
        $staff->client_id      = clientId();
        $staff->nik            = $request->nik;
        $staff->name           = $request->name;
        $staff->birth_date     = Carbon::parse($request->birth_date);
        $staff->joined_date    = Carbon::parse($request->joined_date);
        $staff->department_id  = $request->department_id;
        $staff->status         = $request->status;
        $staff->position_id    = $request->position_id;
        $staff->phone          = $request->phone;
        $staff->email          = $request->email;
        $staff->account_number = $request->account_number;
        $staff->account_bank   = $request->account_bank;
        $staff->account_name   = $request->account_name;
        $staff->save();

        return redirect()->route('client.staff.index')->with('notif_success', 'Staff baru telah berhasil disimpan!');
    }

    public function show(Request $request, $id)
    {
        checkPermissionTo('edit-staff');

        $staff = Staff::findOrFail($id);
        $departments = Department::all();
        $staffPositions = StaffPosition::all();

        return view('client.staff.staff.show', compact('staff', 'departments', 'staffPositions'));
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-staff');

        $staff = Staff::findOrFail($id);
        $departments = Department::all();
        $staffPositions = StaffPosition::all();

        return view('client.staff.staff.edit', compact('staff', 'departments', 'staffPositions'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-staff');

        $this->validate($request, [
            'name'           => 'required',
            'joined_date'    => 'required|date_format:d-m-Y',
            'birth_date'     => 'required|date_format:d-m-Y',
            'phone'          => 'required|min:11|numeric',
            'status'         => 'required|in:Active,Intern,Resign',
            'department_id'  => 'required|integer|exists:master_departments,id,deleted_at,NULL',
            'position_id'    => 'required|integer|exists:master_staff_positions,id,deleted_at,NULL',
            'account_number' => 'required',
            'account_bank'   => 'required',
            'account_name'   => 'required'
        ]);

        $staff                 = Staff::findOrFail($id);
        $staff->client_id      = clientId();
        $staff->name           = $request->name;
        $staff->birth_date     = Carbon::parse($request->birth_date);
        $staff->joined_date    = Carbon::parse($request->joined_date);
        $staff->department_id  = $request->department_id;
        $staff->position_id    = $request->position_id;
        $staff->status         = $request->status;
        $staff->phone          = $request->phone;
        $staff->email          = $request->email;
        $staff->account_number = $request->account_number;
        $staff->account_bank   = $request->account_bank;
        $staff->account_name   = $request->account_name;
        $staff->save();

        return redirect()->route('client.staff.index')->with('notif_success', 'Staff telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-staff');

        $trialStudent = Staff::findOrFail($id);

        $trialStudent->delete();

        return redirect()->route('client.staff.index')->with('notif_success', 'Staff telah berhasil dihapus!');
    }

    public function getData()
    {
        checkPermissionTo('view-staff-list');

        $staffs = Staff::with([
            'department' => function ($qry) {
                $qry->withoutGlobalScopes();
            },
            'position'  => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])->select('staff.*');
        // ->where(DB::raw('year(staff.joined_date)'), year())
        // ->where(DB::raw('month(staff.joined_date)'), month());

        return Datatables::of($staffs)
            ->editColumn('joined_date', function ($staff) {
                return optional($staff->joined_date)->format('d M Y');
            })
            ->addColumn('age', function ($staff) {
                return $staff->birth_date->diffInYears(now()) . ' Years';
            })
            ->editColumn('status', function ($staff) {
                if ($staff->status == 'Active')
                    return '<span class="tag tag-primary tl-tip">Aktif</span>';
                elseif ($staff->status == 'Intern')
                    return '<span class="tag tag-warning tl-tip">Magang</span>';
                elseif ($staff->status == 'Resign')
                    return '<span class="tag tag-danger tl-tip">Resign</span>';
            })
            ->editColumn('active_work', function ($staff) {
                return yearMonthFormat($staff->joined_date);
            })
            ->addColumn('action', function ($staff) {
                $show = '<a href="' . route('client.staff.show', $staff->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Lihat Staff"><i class="icon wb-eye" aria-hidden="true"></i></a>';

                $edit = '<a href="' . route('client.staff.edit', $staff->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Staff"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.staff.destroy', $staff->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Staff"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('show-staff') ? $show : '') . (userCan('edit-staff') ? $edit : '') . (userCan('delete-staff') ? $delete : '');
            })
            ->rawColumns(['joined_date', 'age',  'status', 'action', 'active_work'])
            ->make(true);
    }
}
