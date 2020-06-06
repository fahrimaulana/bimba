<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-department-list');

        return view('client.master.department.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-department');

        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'price' => 'required|numeric'
        ]);

        $department = new Department;
        $department->client_id = clientId();
        $department->name = $request->name;
        $department->code = $request->code;
        $department->price = $request->price;
        $department->save();

        return redirect()->route('client.master.department.index')->with('notif_success', 'New department has been saved successfully!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-department');

        $department = Department::findOrFail($id);
        return view('client.master.department.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-department');

        $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
            'price' => 'required|numeric'
        ]);

        $department = Department::findOrFail($id);
        $department->name = $request->name;
        $department->code = $request->code;
        $department->price = $request->price;
        $department->save();

        return redirect()->route('client.master.department.index')->with('notif_success', 'Department has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-department');

        $department = Department::findOrFail($id);

        $department->delete();

        return redirect()->route('client.master.department.index')->with('notif_success', 'Department has been deleted successfully!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-department-list');

        $departments = Department::query();

        return Datatables::of($departments)
                    ->addColumn('action', function($department) {
                        $edit = '<a href="' . route('client.master.department.edit', $department->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit department"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.department.destroy', $department->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete department"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-department') ? $edit : '') . (userCan('delete-department') ? $delete : '');
                    })
                    ->make(true);
    }
}