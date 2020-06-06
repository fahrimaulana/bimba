<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\StudentPhase;
use App\Http\Controllers\Controller;

class StudentPhaseController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-student-phase-list');

        return view('client.master.student-phase.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-student-phase');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $studentPhase = new StudentPhase;
        $studentPhase->client_id = clientId();
        $studentPhase->name = $request->name;
        $studentPhase->save();

        return redirect()->route('client.master.student-phase.index')->with('notif_success', 'New student phase has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-student-phase');

        $studentPhase = StudentPhase::findOrFail($id);
        return view('client.master.student-phase.edit', compact('studentPhase'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-student-phase');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $studentPhase = StudentPhase::findOrFail($id);
        $studentPhase->name = $request->name;
        $studentPhase->save();

        return redirect()->route('client.master.student-phase.index')->with('notif_success', 'Student phase has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-student-phase');

        $studentPhase = StudentPhase::findOrFail($id);

        $studentPhase->delete();

        return redirect()->route('client.master.student-phase.index')->with('notif_success', 'Student phase has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-student-phase-list');

        $studentPhases = StudentPhase::query();

        return Datatables::of($studentPhases)
                    ->addColumn('action', function($studentPhase) {
                        $edit = '<a href="' . route('client.master.student-phase.edit', $studentPhase->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit student phase"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.student-phase.destroy', $studentPhase->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete student phase"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-student-phase') ? $edit : '') . (userCan('delete-student-phase') ? $delete : '');
                    })
                    ->make(true);
    }
}