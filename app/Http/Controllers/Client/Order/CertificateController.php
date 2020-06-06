<?php

namespace App\Http\Controllers\Client\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction\TransactionDetail;

class CertificateController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-order-certificate-list');

        $transactionDetails = TransactionDetail::with('transaction.student')
            ->whereCategory('Product')
            ->whereHas('product', function ($qry) {
                $qry->where('code', 'STF');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.order.certificate.index', compact('transactionDetails'));
    }

    public function edit($id)
    {
        checkPermissionTo('edit-order-certificate');

        $transactionDetail = TransactionDetail::with('transaction.student')
            ->whereCategory('Product')
            ->whereHas('product', function ($qry) {
                $qry->where('code', 'STF');
            })
            ->findOrFail($id);

        return view('client.order.certificate.edit', compact('transactionDetail'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-order-certificate');

        $this->validate($request, [
            'level' => 'required|integer|in:1,2,3,4',
            'week' => 'required|integer|in:1,2,3,4,5'
        ]);

        $transactionDetail = TransactionDetail::with('transaction.student')
            ->whereCategory('Product')
            ->whereHas('product', function ($qry) {
                $qry->where('code', 'STF');
            })
            ->findOrFail($id);

        $extra['level'] = $request->level;
        $extra['week'] = $request->week;
        $extra['note'] = $request->note;

        $transactionDetail->extra = $extra;
        $transactionDetail->save();

        return redirect()->route('client.order.certificate.index')->with('notif_success', 'Order Certificate telah berhasil diubah!');
    }
}
