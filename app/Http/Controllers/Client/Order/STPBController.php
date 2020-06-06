<?php

namespace App\Http\Controllers\Client\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction\TransactionDetail;

class STPBController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-order-stpb-list');

        $transactionDetails = TransactionDetail::with('transaction.student')
            ->whereCategory('Product')
            ->whereHas('product', function ($qry) {
                $qry->where('code', 'STPB');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.order.stpb.index', compact('transactionDetails'));
    }

    public function edit($id)
    {
        checkPermissionTo('edit-order-stpb');

        $transactionDetail = TransactionDetail::with('transaction.student')
            ->whereCategory('Product')
            ->whereHas('product', function ($qry) {
                $qry->where('code', 'STPB');
            })
            ->findOrFail($id);

        return view('client.order.stpb.edit', compact('transactionDetail'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-order-stpb');

        $this->validate($request, [
            'level_date' => 'required|date_format:d-m-Y',
            'level' => 'required|integer|in:1,2,3,4',
            'week' => 'required|integer|in:1,2,3,4,5'
        ]);

        $transactionDetail = TransactionDetail::with('transaction.student')
            ->whereCategory('Product')
            ->whereHas('product', function ($qry) {
                $qry->where('code', 'STPB');
            })
            ->findOrFail($id);

        $extra['level_date'] = $request->level_date;
        $extra['level'] = $request->level;
        $extra['week'] = $request->week;
        $extra['note'] = $request->note;

        $transactionDetail->extra = $extra;
        $transactionDetail->save();

        return redirect()->route('client.order.stpb.index')->with('notif_success', 'Order STPB telah berhasil diubah!');
    }
}
