<?php

namespace App\Http\Controllers\Client\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module\ModuleTransaction;
use App\Models\Transaction\TransactionDetail;

class OrderModuleController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-order-module-list');

        $weekSelects = collect();
        $year = year();
        $month = month();
        foreach (range(1, 5) as $week) {
            $previousWeek = $week - 1;
            $weekSelects->push("
                CEIL((m.min_stock - (SUM(CASE WHEN mt.date <= '{$year}-{$month}-01' OR (year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week < {$week}) THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END) + SUM(CASE WHEN year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week = {$week} THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END))) / 5) * 5 as w{$week}_qty,
                CEIL((m.min_stock - (SUM(CASE WHEN mt.date <= '{$year}-{$month}-01' OR (year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week < {$week}) THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END) + SUM(CASE WHEN year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week = {$week} THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END))) / 5) * 5 * mt.module_price as w{$week}_price,
                CASE WHEN CEIL((m.min_stock - (SUM(CASE WHEN mt.date <= '{$year}-{$month}-01' OR (year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week < {$previousWeek}) THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END) + SUM(CASE WHEN year(mt.date) = {$year} AND month(mt.date) = {$month} AND mt.week = {$previousWeek} THEN (CASE WHEN mt.type = 'in' THEN mt.qty ELSE -mt.qty END) ELSE 0 END))) / 5) * 5 > 0 THEN 1 ELSE 0 END as w{$week}_status
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

        $modules = collect();
        $maxModuleCount = 1;
        foreach (range(1, 5) as $week) {
            $modules[$week] = $moduleTransactions->where("w{$week}_qty", '>', 0)
                ->values()->toArray();
            $maxModuleCount = count($modules[$week]) > $maxModuleCount
                ? count($modules[$week])
                : $maxModuleCount;
        }

        return view('client.order.module.index', compact('modules', 'maxModuleCount'));
    }
}
