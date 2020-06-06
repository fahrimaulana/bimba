<?php

namespace App\Http\Controllers\Client\Staff;

use Datatables;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Staff\StaffAbsence;
use App\Http\Controllers\Controller;
use App\Models\Master\AbsenceReason;

class AbsenceController extends Controller
{
    public function index()
    {;
        checkPermissionTo('view-staff-absence-list');

        $staffs = Staff::with([
            'position'  => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])->select('staff.*')->where('status', '!=', 'Resign')->get();

        $reasons = AbsenceReason::all();

        return view('client.staff.absence.index', compact('staffs', 'reasons'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-staff-absence');

        $this->validate($request, [
            'absent_date'       => 'required|date_format:d-m-Y',
            'staff_id'          => 'required|integer|exists:staff,id,deleted_at,NULL',
            'absence_reason_id' => 'required|integer|exists:master_absence_reasons,id,deleted_at,NULL'
        ]);

        $absence                    = new StaffAbsence;
        $absence->client_id         = clientId();
        $absence->absent_date       = Carbon::parse($request->absent_date);
        $absence->staff_id          = $request->staff_id;
        $absence->absence_reason_id = $request->absence_reason_id;
        $absence->note              = $request->note;
        $absence->save();

        return redirect()->route('client.staff.absence.index')->with('notif_success', 'Absensi telah berhasil disimpan!');
    }

    public function edit($id, Request $request)
    {
        checkPermissionTo('edit-staff-absence');

        $absence = StaffAbsence::findOrFail($id);
        $staffs = Staff::with([
            'position'  => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])->select('staff.*')->where('status', '!=', 'Resign')->get();
        $reasons = AbsenceReason::all();

        return view('client.staff.absence.edit', compact('absence', 'staffs', 'reasons'));
    }

    public function update($id, Request $request)
    {
        checkPermissionTo('edit-staff-absence');

        $this->validate($request, [
            'absent_date'       => 'required|date_format:d-m-Y',
            'staff_id'          => 'required|integer|exists:staff,id,deleted_at,NULL',
            'absence_reason_id' => 'required|integer|exists:master_absence_reasons,id,deleted_at,NULL'
        ]);

        $absence                    = StaffAbsence::findOrFail($id);
        $absence->absent_date       = Carbon::parse($request->absent_date);
        $absence->staff_id          = $request->staff_id;
        $absence->absence_reason_id = $request->absence_reason_id;
        $absence->note              = $request->note;
        $absence->save();

        return redirect()->route('client.staff.absence.index')->with('notif_success', 'Absensi telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-staff-absence');

        $student = StaffAbsence::findOrFail($id)->delete();

        return redirect()->route('client.staff.absence.index')->with('notif_success', 'Absensi telah berhasil dihapus!');
    }

    public function getData()
    {
        checkPermissionTo('view-staff-absence-list');

        $year = year();
        $month = month();
        $prevMonth = $month - 1;
        $absences = StaffAbsence::with([
            'staff' => function ($qry) {
                $qry->withoutGlobalScopes();
            },
            'staff.position' => function ($qry) {
                $qry->withoutGlobalScopes();
            },
            'staff.department' => function ($qry) {
                $qry->withoutGlobalScopes();
            },
            'absenceReason' => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])
            ->select('staff_absences.*')
            ->whereBetween('absent_date', ["{$year}-{$prevMonth}-26", "{$year}-{$month}-25"]);

        return Datatables::of($absences)
            ->editColumn('absent_date', function ($absence) {
                return optional($absence->absent_date)->format('d M Y');
            })
            ->addColumn('action', function ($absence) {
                $edit = '<a href="' . route('client.staff.absence.edit', $absence->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Absensi"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.staff.absence.destroy', $absence->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Absensi"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-staff-absence') ? $edit : '') . (userCan('delete-staff-absence') ? $delete : '');
            })
            ->rawColumns(['absent_date', 'action'])
            ->make(true);
    }
}
