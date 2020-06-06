<?php

namespace App\Http\Controllers\Client\Order;

use Illuminate\Http\Request;
use App\Enum\Transaction\ExtraSize;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Models\Module\ModuleTransaction;
use App\Models\Product;
use App\Models\Student\Student;
use App\Models\Transaction\TransactionDetail;

class OrderStatisticController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-order-statistic');

        $extraSizes = ExtraSize::all();

        $weekSelects = collect();
        $year = year();
        $month = month();
        foreach (range(1, 5) as $week) {
            $previousWeek = $week - 1;
            $weekSelects->push("
                CEIL((m.min_stock - (SUM(CASE WHEN mt.date <= '{$year}-{$month}-01' OR (year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week < {$week}) THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END) + SUM(CASE WHEN year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week = {$week} THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END))) / 5) * 5 * mt.module_price as w{$week}_price
            ");
        }
        $moduleTransactions = ModuleTransaction::withoutGlobalScopes()
            ->selectRaw("m.code, " . $weekSelects->implode(','))
            ->from('module_transactions as mt')
            ->leftJoin('modules as m', 'm.id', '=', 'mt.module_id')
            ->where('mt.client_id', clientId())
            ->where('m.type', '!=', 'ATK')
            ->whereNull('mt.deleted_at')
            ->whereIn('mt.type', ['in', 'out'])
            ->groupBy(['mt.module_id', 'm.code', 'mt.module_price', 'm.min_stock'])
            ->get();

        $moduleTotal = [];
        foreach (range(1, 5) as $week) {
            $moduleTotal[$week] = 0;
            foreach ($moduleTransactions as $moduleTx) {
                if ($moduleTx["w{$week}_price"] > 0) {
                    $moduleTotal[$week] += $moduleTx["w{$week}_price"];
                }
            }
        }

        $transactions = $this->transactionData();
        $products = Product::all();
        $sizeTotal = [];
        foreach ($transactions as $transaction) {
            $extra = optional($transaction->extra);
            $sizeTotal[$extra['size']] = isset($sizeTotal[$extra['size']])
                ? $sizeTotal[$extra['size']] + 1 : 1;
        }

        $atkStats = $this->atkStatsData();

        $newStudentCount = Student::whereStatus('Active')->whereYear('joined_date', year())->whereMonth('joined_date', month())->count();

        return view('client.order.statistic.index', compact('extraSizes', 'moduleTotal', 'transactions', 'products', 'sizeTotal', 'atkStats', 'newStudentCount'));
    }

    public function transactionData()
    {
        $transactions = Transaction::selectRaw("transactions.extra,
                SUM(CASE WHEN td.category = 'Registration' THEN 1 WHEN p.code = 'ME' THEN td.qty ELSE 0 END) as me_count,
                SUM(CASE WHEN td.category = 'Registration' THEN 1 WHEN p.code = 'TAS' THEN td.qty ELSE 0 END) as tas_count,
                SUM(CASE WHEN p.code = 'KA' THEN td.qty ELSE 0 END) as ka_count,
                SUM(CASE WHEN p.code = 'KA' THEN td.total ELSE 0 END) as ka_total,
                SUM(CASE WHEN p.code = 'STF' THEN td.qty ELSE 0 END) as certificate_count,
                SUM(CASE WHEN p.code = 'STF' THEN td.total ELSE 0 END) as certificate_total,
                SUM(CASE WHEN p.code = 'STPB' THEN td.qty ELSE 0 END) as stpb_count,
                SUM(CASE WHEN p.code = 'STPB' THEN td.total ELSE 0 END) as stpb_total
            ")
            ->leftJoin('transaction_details as td', 'td.transaction_id', '=', 'transactions.id')
            ->leftJoin('products as p', 'p.id', '=', 'td.product_id')
            ->whereYear('transactions.date', year())
            ->whereMonth('transactions.date', month())
            ->groupBy(['transactions.id', 'transactions.extra'])
            ->get();

        return $transactions;
    }

    public function atkStatsData()
    {
        $atkStats = ModuleTransaction::selectRaw("
                COUNT(*) as count,
                SUM(module_price * qty) as total
            ")
            ->whereType('in')
            ->whereHas('module', function ($qry) {
                $qry->where('type', 'ATK');
            })
            ->first();

        return $atkStats;
    }
}
