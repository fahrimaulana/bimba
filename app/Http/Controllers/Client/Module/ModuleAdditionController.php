<?php

namespace App\Http\Controllers\Client\Module;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Http\Controllers\Controller;
use App\Models\Module\ModuleTransaction;

class ModuleAdditionController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-module-addition-list');

        $modules = Module::where('type', '!=', 'ATK')->get();
        $transactions = ModuleTransaction::whereType('in')
            ->whereHas('module', function ($qry) {
                $qry->where('type', '!=', 'ATK');
            })
            ->orderBy('date', 'desc')->get();

        return view('client.module.addition.index', compact('transactions', 'modules'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-module-addition');

        $this->validate($request, [
            'receipt' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'week' => 'required|integer|in:1,2,3,4,5',
            'module_id' => 'required|integer|' . existsOnCurrentClient('modules'),
            'qty' => 'required|integer|min:0'
        ]);

        $module = Module::where('type', '!=', 'ATK')->findOrFail($request->module_id);

        $transaction = new ModuleTransaction;
        $transaction->client_id = clientId();
        $transaction->receipt = $request->receipt;
        $transaction->date = Carbon::parse($request->date);
        $transaction->week = $request->week;
        $transaction->module_id = $request->module_id;
        $transaction->module_price = $module->price;
        $transaction->qty = $request->qty;
        $transaction->type = 'in';
        $transaction->user_id = user()->id;
        $transaction->save();

        return redirect()->route('client.module.addition.index')->with('notif_success', 'Penerimaan modul telah berhasil disimpan!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-module-addition');

        $transaction = ModuleTransaction::whereType('in')->findOrFail($id);
        $modules = Module::where('type', '!=', 'ATK')->get();

        return view('client.module.addition.edit', compact('transaction', 'modules'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-module-addition');

        $this->validate($request, [
            'receipt' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'week' => 'required|integer|in:1,2,3,4,5',
            'module_id' => 'required|integer|' . existsOnCurrentClient('modules'),
            'qty' => 'required|integer|min:0'
        ]);

        $transaction = ModuleTransaction::whereType('in')->findOrFail($id);
        $module = Module::where('type', '!=', 'ATK')->findOrFail($request->module_id);

        $transaction->receipt = $request->receipt;
        $transaction->date = Carbon::parse($request->date);
        $transaction->week = $request->week;
        $transaction->module_id = $request->module_id;
        $transaction->module_price = $module->price;
        $transaction->qty = $request->qty;
        $transaction->save();

        return redirect()->route('client.module.addition.index')->with('notif_success', 'Penerimaan modul telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-module-addition');

        $transaction = ModuleTransaction::whereType('in')->findOrFail($id);
        $transaction->delete();

        return redirect()->route('client.module.addition.index')->with('notif_success', 'Penerimaan modul telah berhasil dihapus!');
    }
}
