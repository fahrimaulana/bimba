<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\StudentOutReason;
use App\Http\Controllers\Controller;

class StudentOutReasonController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-student-out-reason-list');

        return view('client.master.student-out-reason.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-student-out-reason');

        $this->validate($request, [
            'reason' => 'required'
        ]);

        $studentOutReason = new StudentOutReason;
        $studentOutReason->client_id = clientId();
        $studentOutReason->reason = $request->reason;
        $studentOutReason->save();

        return redirect()->route('client.master.student-out-reason.index')->with('notif_success', 'New student out reason has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-student-out-reason');

        $studentOutReason = StudentOutReason::findOrFail($id);
        return view('client.master.student-out-reason.edit', compact('studentOutReason'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-student-out-reason');

        $this->validate($request, [
            'reason' => 'required'
        ]);

        $studentOutReason = StudentOutReason::findOrFail($id);
        $studentOutReason->reason = $request->reason;
        $studentOutReason->save();

        return redirect()->route('client.master.student-out-reason.index')->with('notif_success', 'Student out reason has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-student-out-reason');

        $studentOutReason = StudentOutReason::findOrFail($id);

        $studentOutReason->delete();

        return redirect()->route('client.master.student-out-reason.index')->with('notif_success', 'Student out reason has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-student-out-reason-list');

        $studentOutReasons = StudentOutReason::query();

        return Datatables::of($studentOutReasons)
                    ->addColumn('action', function($studentOutReason) {
                        $edit = '<a href="' . route('client.master.student-out-reason.edit', $studentOutReason->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit student out reason"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.student-out-reason.destroy', $studentOutReason->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete student out reason"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-student-out-reason') ? $edit : '') . (userCan('delete-student-out-reason') ? $delete : '');
                    })
                    ->make(true);
    }
}