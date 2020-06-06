<?php

namespace App\Http\Controllers\Client\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Http\Controllers\Controller;
use App\Models\Module\ModuleTransaction;

class ATKController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-order-atk-list');

        $modules = Module::where('type', 'ATK')->get();
        $transactions = ModuleTransaction::whereType('in')
            ->whereHas('module', function ($qry) {
                $qry->where('type', 'ATK');
            })
            ->orderBy('date', 'desc')->get();

        return view('client.order.atk.index', compact('transactions', 'modules'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-order-atk');

        $this->validate($request, [
            'date' => 'required|date_format:d-m-Y',
            'week' => 'required|integer|in:1,2,3,4,5',
            'module_id' => 'required|integer|' . existsOnCurrentClient('modules'),
            'qty' => 'required|integer|min:0'
        ]);

        $module = Module::where('type', 'ATK')->findOrFail($request->module_id);

        $transaction = new ModuleTransaction;
        $transaction->client_id = clientId();
        $transaction->date = Carbon::parse($request->date);
        $transaction->week = $request->week;
        $transaction->module_id = $request->module_id;
        $transaction->module_price = $module->price;
        $transaction->qty = $request->qty;
        $transaction->type = 'in';
        $transaction->note = $request->note;
        $transaction->user_id = user()->id;
        $transaction->save();

        return redirect()->route('client.order.atk.index')->with('notif_success', 'Order ATK telah berhasil disimpan!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-order-atk');

        $transaction = ModuleTransaction::whereType('in')->findOrFail($id);
        $modules = Module::where('type', 'ATK')->get();

        return view('client.order.atk.edit', compact('transaction', 'modules'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-order-atk');

        $this->validate($request, [
            'date' => 'required|date_format:d-m-Y',
            'week' => 'required|integer|in:1,2,3,4,5',
            'module_id' => 'required|integer|' . existsOnCurrentClient('modules'),
            'qty' => 'required|integer|min:0'
        ]);

        $transaction = ModuleTransaction::whereType('in')->findOrFail($id);
        $module = Module::where('type', 'ATK')->findOrFail($request->module_id);

        $transaction->date = Carbon::parse($request->date);
        $transaction->week = $request->week;
        $transaction->module_id = $request->module_id;
        $transaction->module_price = $module->price;
        $transaction->qty = $request->qty;
        $transaction->note = $request->note;
        $transaction->save();

        return redirect()->route('client.order.atk.index')->with('notif_success', 'Order ATK telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-order-atk');

        $transaction = ModuleTransaction::whereType('in')->findOrFail($id);
        $transaction->delete();

        return redirect()->route('client.order.atk.index')->with('notif_success', 'Order ATK telah berhasil dihapus!');
    }
}
