<?php

namespace App\Http\Controllers\Client\Management;

use Datatables;
use Illuminate\Http\Request;
use App\Models\UserManagement\Role;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Permission;

class RoleController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-role-list');

        return view('backend.management.role.index');
    }

    public function create()
    {
        checkPermissionTo('create-role');

        $permissionGroups = Permission::all()->groupBy('group');

        return view('backend.management.role.create', compact('permissionGroups'));
    }

    public function store()
    {
        checkPermissionTo('create-role');

        $role = Role::where('name', request('name'))->first();
        if ($role) return validationError('The name has already been taken.');

        $this->validate(request(), [
            'name'              => 'required',
            'display_name'      => 'required',
            'permission_ids.*'  => 'exists:permissions,id,scope,' . platform()
        ]);

        $role                   = new Role;
        $role->name             = request('name');
        $role->display_name     = request('display_name');
        $role->platform         = platform();
        $role->client_id        = $role->platform == 'Client' ? clientId() : null;
        $role->save();

        $role->syncPermissions(request('permission_ids'));

        return redirect()->route('client.management.role.index')->with('notif_success', 'New role has been added successfully!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-role');

        $role = Role::findOrFail($id);
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        $permissionGroups = Permission::all()->groupBy('group');

        return view('backend.management.role.edit', compact('role', 'permissionGroups', 'rolePermissionIds'));
    }

    public function update($id)
    {
        checkPermissionTo('edit-role');

        $role = Role::findOrFail($id);

        $roleData = Role::where('name', request('name'))
                        ->where('id', '!=', $id)
                        ->first();
        if ($roleData != null) return validationError('The name has already been taken.');

        $this->validate(request(), [
            'name'              => 'required',
            'display_name'      => 'required',
            'permission_ids.*'  => 'exists:permissions,id,scope,' . platform()
        ]);

        $role->name         = request('name');
        $role->display_name = request('display_name');
        $role->save();

        $role->syncPermissions(request('permission_ids'));

        return redirect()->route('client.management.role.index')->with('notif_success', 'The role has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-role');

        $role = Role::findOrFail($id)->delete();

        return redirect()->back()->with('notif_success', 'Role has been deleted successfully!');
    }

    public function getData()
    {
        checkPermissionTo('view-role-list');

        $roles = Role::query();

        return Datatables::of($roles)
                    ->addColumn('action', function($role) {
                        $edit = '<a href="' . route('client.management.role.edit', $role->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                        $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.management.role.destroy', $role->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                        return (userCan('edit-role') ? $edit : '') . (userCan('delete-role') ? $delete : '');
                    })
                    ->rawColumns(['action'])
                    ->make(true);
    }
}
