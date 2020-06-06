<?php

namespace App\Http\Controllers\Client\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Master\SpecialAllowance;
use App\Models\Master\SpecialAllowanceGroup;
use App\Http\Controllers\Controller;

class SpecialAllowanceController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-special-allowance-list');

        $specialAllowanceGroups = SpecialAllowanceGroup::all();

        return view('client.master.special-allowance.index', compact('specialAllowanceGroups'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-special-allowance');

        $this->validate($request, [
            'group_id' => 'required|integer|' . existsOnCurrentClient('master_special_allowance_groups'),
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $specialAllowance = new SpecialAllowance;
        $specialAllowance->client_id = clientId();
        $specialAllowance->group_id = $request->group_id;
        $specialAllowance->name = $request->name;
        $specialAllowance->price = $request->price;
        $specialAllowance->save();

        return redirect()->route('client.master.special-allowance.index')->with('notif_success', 'Tunjangan Khusus baru telah berhasil disimpan!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-special-allowance');

        $specialAllowance = SpecialAllowance::findOrFail($id);
        $specialAllowanceGroups = SpecialAllowanceGroup::all();

        return view('client.master.special-allowance.edit', compact('specialAllowance', 'specialAllowanceGroups'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-special-allowance');

        $this->validate($request, [
            'group_id' => 'required|integer|' . existsOnCurrentClient('master_special_allowance_groups'),
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $specialAllowance = SpecialAllowance::findOrFail($id);
        $specialAllowance->group_id = $request->group_id;
        $specialAllowance->name = $request->name;
        $specialAllowance->price = $request->price;
        $specialAllowance->save();

        return redirect()->route('client.master.special-allowance.index')->with('notif_success', 'Tunjangan Khusus telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-special-allowance');

        $specialAllowance = SpecialAllowance::findOrFail($id);

        $specialAllowance->delete();

        return redirect()->route('client.master.special-allowance.index')->with('notif_success', 'Tunjangan Khusus telah berhasil dihapus!');
    }

    public function getData()
    {
        checkPermissionTo('view-special-allowance-list');

        $specialAllowances = SpecialAllowance::with([
            'group' => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])->select('master_special_allowances.*');

        return Datatables::of($specialAllowances)
            ->addColumn('action', function ($specialAllowance) {
                $edit = '<a href="' . route('client.master.special-allowance.edit', $specialAllowance->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah Tunjangan Khusus"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.master.special-allowance.destroy', $specialAllowance->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus Tunjangan Khusus"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-special-allowance') ? $edit : '') . (userCan('delete-special-allowance') ? $delete : '');
            })
            ->make(true);
    }
}
