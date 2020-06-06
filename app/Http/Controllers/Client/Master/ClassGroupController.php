<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\ClassGroup;
use App\Http\Controllers\Controller;

class ClassGroupController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-class-group-list');

        return view('client.master.class-group.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-class-group');

        $this->validate($request, [
            'name' => 'required',
            'total_teacher' => 'required',
            'total_student' => 'required'
        ]);

        $classGroup = new ClassGroup;
        $classGroup->client_id = clientId();
        $classGroup->name = $request->name;
        $classGroup->total_teacher = $request->total_teacher;
        $classGroup->total_student = $request->total_student;
        $classGroup->save();

        return redirect()->route('client.master.class-group.index')->with('notif_success', 'New class group has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-class-group');

        $classGroup = ClassGroup::findOrFail($id);
        return view('client.master.class-group.edit', compact('classGroup'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-class-group');

        $this->validate($request, [
            'name' => 'required',
            'total_teacher' => 'required',
            'total_student' => 'required'
        ]);

        $classGroup = ClassGroup::findOrFail($id);
        $classGroup->name = $request->name;
        $classGroup->total_teacher = $request->total_teacher;
        $classGroup->total_student = $request->total_student;
        $classGroup->save();

        return redirect()->route('client.master.class-group.index')->with('notif_success', 'Class group has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-class-group');

        $classGroup = ClassGroup::findOrFail($id);

        $classGroup->delete();

        return redirect()->route('client.master.class-group.index')->with('notif_success', 'Class group has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-class-group-list');

        $classGroups = ClassGroup::query();

        return Datatables::of($classGroups)
                    ->addColumn('action', function($classGroup) {
                        $edit = '<a href="' . route('client.master.class-group.edit', $classGroup->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit class group"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.class-group.destroy', $classGroup->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete class group"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-class-group') ? $edit : '') . (userCan('delete-class-group') ? $delete : '');
                    })
                    ->make(true);
    }
}