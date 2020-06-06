<?php

namespace App\Http\Controllers\Client\Recap;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PettyCash\PettyCash;
use App\Http\Controllers\Controller;
use App\Models\Student\TrialStudent;
use App\Models\Transaction\Transaction;
use App\Models\Master\PettyCashCategory;

class RecapController extends Controller
{
    public function index($from=null, $to=null)
    {
        checkPermissionTo('view-recap-list');

        $from        = $from ?: Carbon::now()->startOfMonth()->format('d-m-Y');
        $to          = $to ?: Carbon::now()->endOfMonth()->format('d-m-Y');
        $transaction = $this->getTransactionQuery($from, $to);
        $pettyCastTransaction = $this->getPettyCashQuery($from, $to);

        $initialSaldo = PettyCash::withoutGlobalScope('period')
            ->selectRaw("IFNULL(SUM(IFNULL(debit, 0) - IFNULL(credit, 0)), 0) as saldo")
            ->where('date', '<', Carbon::parse($from)->format('Y-m-d'))
            ->first()
            ->saldo;

        $endingSaldo = PettyCash::withoutGlobalScope('period')
            ->selectRaw("IFNULL(SUM(IFNULL(debit, 0) - IFNULL(credit, 0)), 0) as saldo")
            ->where('date', '<=', Carbon::parse($to)->format('Y-m-d'))
            ->first()
            ->saldo;


        $receiptTransaction = $transaction
            ->selectRaw("
                SUM(CASE WHEN tcd.category = 'Registration' THEN tcd.total ELSE 0 END) AS daftar,
                SUM(CASE WHEN tcd.discount THEN tcd.discount ELSE 0 END) AS voucher,
                SUM(CASE WHEN tcd.category = 'SPP' THEN tcd.total ELSE 0 END) AS spp,

                SUM(CASE WHEN (transactions.payment_method ='Cash')
                    THEN CASE WHEN (tcd.category = 'SPP') THEN tcd.total ELSE 0 END ELSE 0 END) AS cash,

                SUM(CASE WHEN (transactions.payment_method ='Transfer')
                    THEN CASE WHEN (tcd.category = 'SPP') THEN tcd.total ELSE 0 END ELSE 0 END) AS transfer,
                SUM(CASE WHEN (transactions.payment_method ='Edc')
                    THEN CASE WHEN (tcd.category = 'SPP') THEN tcd.total ELSE 0 END ELSE 0 END) AS edc,
                SUM(CASE WHEN tcd.product_id = '1' THEN tcd.total ELSE 0 END) AS ka,
                SUM(CASE WHEN tcd.product_id = '2' THEN tcd.total ELSE 0 END) AS me,
                SUM(CASE WHEN tcd.product_id = '3' THEN tcd.total ELSE 0 END) AS sertifikat,
                SUM(CASE WHEN tcd.product_id = '4' THEN tcd.total ELSE 0 END) AS STPb,
                SUM(CASE WHEN tcd.product_id = '5' THEN tcd.total ELSE 0 END) AS tas,
                SUM(CASE WHEN tcd.category = 'Event' THEN tcd.total ELSE 0 END) AS event,
                SUM(CASE WHEN tcd.category = 'Others' THEN tcd.total ELSE 0 END) AS other")
            ->first();

        $totalTransaction = $transaction
            ->selectRaw("SUM(tcd.total) AS total")
            ->first();

        $totalTransactionDetailMethods = $this->getTransactionQuery($from, $to)
            ->selectRaw("
                transactions.payment_method,
                CASE
                    WHEN transactions.payment_method ='Cash' THEN
                        IFNULL(SUM(tcd.total),0)
                    WHEN transactions.payment_method ='Transfer' THEN
                        IFNULL(SUM(tcd.total),0)
                    ELSE
                        IFNULL(SUM(tcd.total),0)
                END payment_method_total
            ")
            ->groupBy('transactions.payment_method')->get();

        $pettyCashCategories = PettyCashCategory::where('type', '=', 'Kredit')->get();
        $productTransactions   = Transaction::productTransaction($from, $to)->get();


        $totalSpending = $this->getPettyCashQuery($from, $to)
            ->selectRaw("SUM(IFNULL(petty_cash.credit, 0)) as total_spending")
            ->where('category.type', '=', 'Kredit')->first();

        $spendingPettyCashs = $this->getPettyCashQuery($from, $to)
            ->selectRaw("category.id, SUM(IFNULL(petty_cash.credit, 0)) as total_spending")
            ->where('category.type', '=', 'Kredit')
            ->groupBy(['category.id'])->get();

        $totalPettyCash = $this->getPettyCashQuery($from, $to)
            ->selectRaw("
                SUM(IFNULL(petty_cash.debit, 0)) as total_petty_cash
            ")->where('category.type', '=', 'Debit')->first()->total_petty_cash;

        return view('client.recap.index', compact('receiptTransaction', 'productTransactions', 'from', 'to', 'totalTransaction', 'totalTransactionDetailMethods', 'pettyCashCategories', 'spendingPettyCashs', 'totalPettyCash', 'totalSpending', 'initialSaldo', 'endingSaldo'));
    }

    private function getTransactionQuery($from, $to)
    {
        $from = Carbon::parse($from)->format('Y-m-d');
        $to = Carbon::parse($to)->format('Y-m-d');

        return Transaction::withoutGlobalScopes()
                        ->leftJoin('transaction_details as tcd', 'transactions.id', '=', 'tcd.transaction_id')
                        ->where('transactions.client_id', clientId())
                        ->whereBetween('transactions.date', [$from, $to])
                        ->whereNull('tcd.deleted_at');
    }

    private function getPettyCashQuery($from, $to)
    {
        $from = Carbon::parse($from)->format('Y-m-d');
        $to = Carbon::parse($to)->format('Y-m-d');

        return PettyCash::withoutGlobalScopes()
                ->from('petty_cash_transactions AS petty_cash')
                ->leftJoin('master_petty_cash_categories as category', 'petty_cash.category_id', '=', 'category.id')
                ->where('petty_cash.client_id', clientId())
                ->whereBetween('petty_cash.date', [$from, $to])
                ->whereNull('petty_cash.deleted_at');
    }
}
