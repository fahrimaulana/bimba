<?php

namespace App\Http\Controllers\Client\Order;

use Illuminate\Http\Request;
use App\Enum\Transaction\ExtraSize;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;

class OrderAttributeController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-order-attribute-list');

        $extraSizes = ExtraSize::all();
        $transactions = Transaction::selectRaw("
                transactions.id, transactions.receipt_no, transactions.date, s.nim as student_nim, s.name as student_name, transactions.extra,
                SUM(CASE WHEN td.category = 'Registration' THEN 1 WHEN p.code = 'KA' THEN td.qty ELSE 0 END) as ka_count,
                SUM(CASE WHEN td.category = 'Registration' THEN 1 WHEN p.code = 'ME' THEN td.qty ELSE 0 END) as me_count,
                SUM(CASE WHEN td.category = 'Registration' THEN 1 WHEN p.code = 'TAS' THEN td.qty ELSE 0 END) as tas_count
            ")
            ->leftJoin('transaction_details as td', 'td.transaction_id', '=', 'transactions.id')
            ->leftJoin('products as p', 'p.id', '=', 'td.product_id')
            ->leftJoin('students as s', 's.id', '=', 'transactions.student_id')
            ->whereYear('transactions.date', year())
            ->whereMonth('transactions.date', month())
            ->where(function ($qry) {
                $qry->whereIn('p.code', ['KA', 'ME', 'TAS'])
                    ->orWhere('td.category', 'Registration');
            })
            ->groupBy(['transactions.id', 'transactions.receipt_no', 'transactions.date', 's.id', 's.nim', 's.name', 'transactions.extra'])
            ->get();

        return view('client.order.attribute.index', compact('extraSizes', 'transactions'));
    }

    public function edit($id)
    {
        checkPermissionTo('edit-order-attribute');

        $extraSizes = ExtraSize::all();
        $transaction = Transaction::selectRaw("
                transactions.id, transactions.receipt_no, transactions.date, s.nim as student_nim, s.name as student_name, transactions.extra
            ")
            ->leftJoin('students as s', 's.id', '=', 'transactions.student_id')
            ->findOrFail($id);

        return view('client.order.attribute.edit', compact('extraSizes', 'transaction'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-order-attribute');

        $this->validate($request, [
            'size' => 'required|in:' . implode(',', ExtraSize::keys())
        ]);

        $transaction = Transaction::selectRaw("
                transactions.id, transactions.receipt_no, transactions.date, s.nim as student_nim, s.name as student_name, transactions.extra
            ")
            ->leftJoin('students as s', 's.id', '=', 'transactions.student_id')
            ->findOrFail($id);

        $extra['size'] = $request->size;
        $extra['note'] = $request->note;

        $transaction->extra = $extra;
        $transaction->save();

        return redirect()->route('client.order.attribute.index')->with('notif_success', 'Order KA | ME | Tas telah berhasil diubah!');
    }
}
