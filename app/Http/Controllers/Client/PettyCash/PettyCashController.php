<?php

namespace App\Http\Controllers\Client\PettyCash;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PettyCash\PettyCash;
use App\Http\Controllers\Controller;
use App\Models\Master\PettyCashCategory;

class PettyCashController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-petty-cash-transaction-list');

        $initialSaldo = PettyCash::withoutGlobalScope('period')
            ->selectRaw("IFNULL(SUM(IFNULL(debit, 0) - IFNULL(credit, 0)), 0) as saldo")
            ->whereYear('date', '<=', year())
            ->whereMonth('date', '<', month())
            ->first()
            ->saldo;
        $pettyCashTransactions = PettyCash::orderBy('date')->get();
        $categories = PettyCashCategory::all();

        return view('client.petty-cash.index', compact('categories', 'pettyCashTransactions', 'initialSaldo'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-petty-cash-transaction');

        $this->validate($request, [
            'receipt_no' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'category_id' => 'required|integer|' . existsOnCurrentClient('master_petty_cash_categories'),
            'note' => 'required',
            'value' => 'required|integer'
        ]);

        $category = PettyCashCategory::findOrFail($request->category_id);
        $type = $category->type === 'Kredit' ? 'credit' : 'debit';

        $pettyCash = new PettyCash;
        $pettyCash->client_id = clientId();
        $pettyCash->date = Carbon::parse($request->date);
        $pettyCash->receipt_no = $request->receipt_no;
        $pettyCash->category_id = $request->category_id;
        $pettyCash->note = $request->note;
        $pettyCash->{$type} = $request->value;
        $pettyCash->save();

        return redirect()->route('client.petty-cash.index')->with('notif_success', 'Transaksi petty cash telah berhasil disimpan!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-petty-cash-transaction');

        $transaction = PettyCash::findOrFail($id);
        $categories = PettyCashCategory::all();

        return view('client.petty-cash.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-petty-cash-transaction');
        $this->validate($request, [
            'receipt_no' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'category_id' => 'required|integer|' . existsOnCurrentClient('master_petty_cash_categories'),
            'note' => 'required',
            'value' => 'required|integer'
        ]);

        $pettyCash = PettyCash::findOrFail($id);
        $category = PettyCashCategory::findOrFail($request->category_id);
        $type = $category->type === 'Kredit' ? 'credit' : 'debit';

        $pettyCash->date = Carbon::parse($request->date);
        $pettyCash->receipt_no = $request->receipt_no;
        $pettyCash->category_id = $request->category_id;
        $pettyCash->note = $request->note;
        $pettyCash->{$type} = $request->value;
        $pettyCash->save();

        return redirect()->route('client.petty-cash.index')->with('notif_success', 'Transaksi petty cash telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-petty-cash-transaction');

        $pettyCash = PettyCash::findOrFail($id);

        $pettyCash->delete();

        return redirect()->route('client.petty-cash.index')->with('notif_success', 'Transaksi petty cash telah berhasil dihapus!');
    }
}
