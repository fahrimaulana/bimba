<?php

namespace App\Http\Controllers\Client\Module;

use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Http\Controllers\Controller;
use App\Models\Module\ModuleTransaction;

class ModuleUsageController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-module-usage-list');

        $modules = Module::where('type', '!=', 'ATK')->get();
        $staffArr = Staff::all();
        $transactions = ModuleTransaction::whereType('out')
            ->whereHas('module', function ($qry) {
                $qry->where('type', '!=', 'ATK');
            })
            ->orderBy('date', 'desc')
            ->get();

        return view('client.module.usage.index', compact('transactions', 'modules', 'staffArr'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-module-usage');

        $this->validate($request, [
            'date' => 'required|date_format:d-m-Y',
            'week' => 'required|integer|in:1,2,3,4,5',
            'module_id' => 'required|integer|' . existsOnCurrentClient('modules'),
            'qty' => 'required|integer|min:0',
            'staff_id' => 'required|integer|' . existsOnCurrentClient('staff'),
        ]);

        $module = Module::where('type', '!=', 'ATK')->findOrFail($request->module_id);

        $transaction = new ModuleTransaction;
        $transaction->client_id = clientId();
        $transaction->date = Carbon::parse($request->date);
        $transaction->week = $request->week;
        $transaction->module_id = $request->module_id;
        $transaction->module_price = $module->price;
        $transaction->qty = $request->qty;
        $transaction->type = 'out';
        $transaction->user_id = user()->id;
        $transaction->staff_id = $request->staff_id;
        $transaction->save();

        return redirect()->route('client.module.usage.index')->with('notif_success', 'Pemakaian modul baru telah berhasil disimpan!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-module-usage');

        $transaction = ModuleTransaction::whereType('out')->findOrFail($id);
        $staffArr = Staff::all();
        $modules = Module::where('type', '!=', 'ATK')->get();

        return view('client.module.usage.edit', compact('transaction', 'modules', 'staffArr'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-module-usage');

        $this->validate($request, [
            'date' => 'required|date_format:d-m-Y',
            'week' => 'required|integer|in:1,2,3,4,5',
            'module_id' => 'required|integer|' . existsOnCurrentClient('modules'),
            'qty' => 'required|integer|min:0',
            'staff_id' => 'required|integer|' . existsOnCurrentClient('staff'),
        ]);

        $transaction = ModuleTransaction::whereType('out')->findOrFail($id);
        $module = Module::where('type', '!=', 'ATK')->findOrFail($request->module_id);

        $transaction->date = Carbon::parse($request->date);
        $transaction->week = $request->week;
        $transaction->module_id = $request->module_id;
        $transaction->module_price = $module->price;
        $transaction->qty = $request->qty;
        $transaction->staff_id = $request->staff_id;
        $transaction->save();

        return redirect()->route('client.module.usage.index')->with('notif_success', 'Pemakaian modul telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-module-usage');

        $transaction = ModuleTransaction::whereType('out')->findOrFail($id);
        $transaction->delete();

        return redirect()->route('client.module.usage.index')->with('notif_success', 'Pemakaian modul telah berhasil dihapus!');
    }
}
