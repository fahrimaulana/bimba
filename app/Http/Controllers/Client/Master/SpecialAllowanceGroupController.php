<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\SpecialAllowanceGroup;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;

class SpecialAllowanceGroupController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-special-allowance-group-list');

        $departments = Department::all();

        return view('client.master.special-allowance-group.index', compact('departments'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-special-allowance-group');

        $this->validate($request, [
            'name' => 'required',
            'department_id' => 'nullable|integer|' . existsOnCurrentClient('master_departments')
        ]);

        $specialAllowanceGroup = new SpecialAllowanceGroup;
        $specialAllowanceGroup->client_id = clientId();
        $specialAllowanceGroup->name = $request->name;
        $specialAllowanceGroup->department_id = $request->department_id;
        $specialAllowanceGroup->save();

        return redirect()->route('client.master.special-allowance-group.index')->with('notif_success', 'Grup tunjangan khusus baru telah berhasil disimpan!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-special-allowance-group');

        $specialAllowanceGroup = SpecialAllowanceGroup::findOrFail($id);
        $departments = Department::all();

        return view('client.master.special-allowance-group.edit', compact('specialAllowanceGroup', 'departments'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-special-allowance-group');

        $this->validate($request, [
            'name' => 'required',
            'department_id' => 'nullable|integer|' . existsOnCurrentClient('master_departments')
        ]);

        $specialAllowanceGroup = SpecialAllowanceGroup::findOrFail($id);
        $specialAllowanceGroup->name = $request->name;
        $specialAllowanceGroup->department_id = $request->department_id;
        $specialAllowanceGroup->save();

        return redirect()->route('client.master.special-allowance-group.index')->with('notif_success', 'Grup tunjangan khusus telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-special-allowance-group');

        $specialAllowanceGroup = SpecialAllowanceGroup::findOrFail($id);

        $specialAllowanceGroup->delete();

        return redirect()->route('client.master.special-allowance-group.index')->with('notif_success', 'Grup tunjangan khusus telah berhasil dihapus!');
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-special-allowance-group-list');

        $specialAllowanceGroups = SpecialAllowanceGroup::with([
            'department' => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])
            ->select('master_special_allowance_groups.*');

        return Datatables::of($specialAllowanceGroups)
            ->addColumn('action', function ($specialAllowanceGroup) {
                $edit = '<a href="' . route('client.master.special-allowance-group.edit', $specialAllowanceGroup->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Grup tunjangan khusus"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.special-allowance-group.destroy', $specialAllowanceGroup->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Grup tunjangan khusus"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-special-allowance-group') ? $edit : '') . (userCan('delete-special-allowance-group') ? $delete : '');
            })
            ->make(true);
    }
}
