<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\StaffPosition;
use App\Http\Controllers\Controller;

class StaffPositionController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-staff-position-list');

        return view('client.master.staff-position.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-staff-position');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $staffPosition = new StaffPosition;
        $staffPosition->client_id = clientId();
        $staffPosition->name = $request->name;
        $staffPosition->save();

        return redirect()->route('client.master.staff-position.index')->with('notif_success', 'New staff position has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-staff-position');

        $staffPosition = StaffPosition::findOrFail($id);
        return view('client.master.staff-position.edit', compact('staffPosition'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-staff-position');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $staffPosition = StaffPosition::findOrFail($id);
        $staffPosition->name = $request->name;
        $staffPosition->save();

        return redirect()->route('client.master.staff-position.index')->with('notif_success', 'Staff position has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-staff-position');

        $staffPosition = StaffPosition::findOrFail($id);

        $staffPosition->delete();

        return redirect()->route('client.master.staff-position.index')->with('notif_success', 'Staff position has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-staff-position-list');

        $staffPositions = StaffPosition::query();

        return Datatables::of($staffPositions)
                    ->addColumn('action', function($staffPosition) {
                        $edit = '<a href="' . route('client.master.staff-position.edit', $staffPosition->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit staff position"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.staff-position.destroy', $staffPosition->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete staff position"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-staff-position') ? $edit : '') . (userCan('delete-staff-position') ? $delete : '');
                    })
                    ->make(true);
    }
}