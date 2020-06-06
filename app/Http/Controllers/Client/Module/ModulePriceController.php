<?php

namespace App\Http\Controllers\Client\Module;

use Datatables;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Http\Controllers\Controller;

class ModulePriceController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-module-price-list');

        return view('client.module.price.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-module-price');

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'min_stock' => 'required|integer',
            'type' => 'nullable|in:Modul biMBA,Modul English,ATK'
        ]);

        $module = new Module;
        $module->client_id = clientId();
        $module->code = $request->code;
        $module->name = $request->name;
        $module->price = $request->price;
        $module->level = $request->level;
        $module->type = $request->type;
        $module->min_stock = $request->min_stock;
        $module->save();

        return redirect()->route('client.module.price.index')->with('notif_success', 'Modul baru telah berhasil disimpan!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-module-price');

        $module = Module::findOrFail($id);
        return view('client.module.price.edit', compact('module'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-module-price');

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'min_stock' => 'required|integer',
            'type' => 'nullable|in:Modul biMBA,Modul English,ATK'
        ]);

        $module = Module::findOrFail($id);
        $module->code = $request->code;
        $module->name = $request->name;
        $module->price = $request->price;
        $module->level = $request->level;
        $module->type = $request->type;
        $module->min_stock = $request->min_stock;
        $module->save();

        return redirect()->route('client.module.price.index')->with('notif_success', 'Modul telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-module-price');

        $module = Module::findOrFail($id);

        $module->delete();

        return redirect()->route('client.module.price.index')->with('notif_success', 'Modul telah berhasil dihapus!');
    }

    public function getData()
    {
        checkPermissionTo('view-module-price-list');

        $modules = Module::query();

        return Datatables::of($modules)
            ->addColumn('action', function ($module) {
                $edit = '<a href="' . route('client.module.price.edit', $module->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah modul"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.module.price.destroy', $module->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus modul"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-module-price') ? $edit : '') . (userCan('delete-module-price') ? $delete : '');
            })
            ->make(true);
    }
}
