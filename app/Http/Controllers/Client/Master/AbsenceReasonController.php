<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\AbsenceReason;
use App\Http\Controllers\Controller;
use App\Enum\Master\MasterClass\AbsenceReasonStatus;

class AbsenceReasonController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-absence-reason-list');

        return view('client.master.absence-reason.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-absence-reason');

        $this->validate($request, [
            'reason' => 'required',
            'status' => 'required|in:' . join(',', AbsenceReasonStatus::keys())
        ]);

        $absenceReason = new AbsenceReason;
        $absenceReason->client_id = clientId();
        $absenceReason->reason = $request->reason;
        $absenceReason->status = $request->status;
        $absenceReason->save();

        return redirect()->route('client.master.absence-reason.index')->with('notif_success', 'New absence reason has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-absence-reason');

        $absenceReason = AbsenceReason::findOrFail($id);
        return view('client.master.absence-reason.edit', compact('absenceReason'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-absence-reason');

        $this->validate($request, [
            'reason' => 'required',
            'status' => 'required|in:' . join(',', AbsenceReasonStatus::keys())
        ]);

        $absenceReason = AbsenceReason::findOrFail($id);
        $absenceReason->reason = $request->reason;
        $absenceReason->status = $request->status;
        $absenceReason->save();

        return redirect()->route('client.master.absence-reason.index')->with('notif_success', 'Absence reason has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-absence-reason');

        $absenceReason = AbsenceReason::findOrFail($id);

        $absenceReason->delete();

        return redirect()->route('client.master.absence-reason.index')->with('notif_success', 'Absence reason has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-absence-reason-list');

        $absenceReasons = AbsenceReason::query();

        return Datatables::of($absenceReasons)
            ->addColumn('action', function ($absenceReason) {
                $edit = '<a href="' . route('client.master.absence-reason.edit', $absenceReason->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit absence reason"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.absence-reason.destroy', $absenceReason->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete absence reason"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-absence-reason') ? $edit : '') . (userCan('delete-absence-reason') ? $delete : '');
            })
            ->make(true);
    }
}
