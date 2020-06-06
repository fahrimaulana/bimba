<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\Grade;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-grade-list');

        return view('client.master.grade.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-grade');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $grade = new Grade;
        $grade->client_id = clientId();
        $grade->name = $request->name;
        $grade->save();

        return redirect()->route('client.master.grade.index')->with('notif_success', 'New grade has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-grade');

        $grade = Grade::findOrFail($id);
        return view('client.master.grade.edit', compact('grade'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-grade');

        $this->validate($request, [
            'name' => 'required'
        ]);

        $grade = Grade::findOrFail($id);
        $grade->name = $request->name;
        $grade->save();

        return redirect()->route('client.master.grade.index')->with('notif_success', 'Grade has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-grade');

        $grade = Grade::findOrFail($id);

        $grade->delete();

        return redirect()->route('client.master.grade.index')->with('notif_success', 'Grade has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-grade-list');

        $grades = Grade::query();

        return Datatables::of($grades)
                    ->addColumn('action', function($grade) {
                        $edit = '<a href="' . route('client.master.grade.edit', $grade->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit grade"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.grade.destroy', $grade->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete grade"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-grade') ? $edit : '') . (userCan('delete-grade') ? $delete : '');
                    })
                    ->make(true);
    }
}