<?php

namespace App\Http\Controllers\Client\Module;

use App\Models\Module\Module;
use App\Http\Controllers\Controller;
use App\Models\Module\ModuleTransaction;

class ModuleStatisticController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-module-statistic');

        $stats = $this->data();

        return view('client.module.statistic.index', compact('stats'));
    }

    public function data()
    {
        $moduleAdditionQry = ModuleTransaction::selectRaw("
                module_id,
                SUM(CASE WHEN week = 1 THEN qty ELSE 0 END) as addition_w1,
                SUM(CASE WHEN week = 2 THEN qty ELSE 0 END) as addition_w2,
                SUM(CASE WHEN week = 3 THEN qty ELSE 0 END) as addition_w3,
                SUM(CASE WHEN week = 4 THEN qty ELSE 0 END) as addition_w4,
                SUM(CASE WHEN week = 5 THEN qty ELSE 0 END) as addition_w5,
                SUM(qty) as total_addition
            ")
            ->where('type', 'in')
            ->groupBy('module_id');

        $moduleDeductionQry = ModuleTransaction::selectRaw("
                module_id,
                SUM(CASE WHEN week = 1 THEN qty ELSE 0 END) as deduction_w1,
                SUM(CASE WHEN week = 2 THEN qty ELSE 0 END) as deduction_w2,
                SUM(CASE WHEN week = 3 THEN qty ELSE 0 END) as deduction_w3,
                SUM(CASE WHEN week = 4 THEN qty ELSE 0 END) as deduction_w4,
                SUM(CASE WHEN week = 5 THEN qty ELSE 0 END) as deduction_w5,
                SUM(qty) as total_deduction
            ")
            ->where('type', 'out')
            ->groupBy('module_id');

        $moduleOpnameQry = ModuleTransaction::selectRaw("
                module_id,
                SUM(qty) as opname
            ")
            ->where('type', 'opname')
            ->groupBy('module_id');

        $lastMonthModuleTransactionQry = ModuleTransaction::withoutGlobalScope('period')
            ->selectRaw("
                module_id,
                SUM(CASE WHEN type = 'in' THEN qty ELSE -qty END) as initial_stock
            ")
            ->whereYear('date', '<=', year())
            ->whereMonth('date', '<', month())
            ->whereIn('type', ['in', 'out'])
            ->groupBy('module_id');

        $totalStockQry = 'COALESCE(ma.total_addition, 0) - COALESCE(md.total_deduction, 0)';
        $endingStockQry = "COALESCE(mi.initial_stock, 0) + {$totalStockQry}";

        $stats = Module::selectRaw("
                SUM(COALESCE(ma.addition_w1, 0) * modules.price) as addition_w1,
                SUM(COALESCE(md.deduction_w1, 0) * modules.price) as deduction_w1,
                SUM(COALESCE(ma.addition_w2, 0) * modules.price) as addition_w2,
                SUM(COALESCE(md.deduction_w2, 0) * modules.price) as deduction_w2,
                SUM(COALESCE(ma.addition_w3, 0) * modules.price) as addition_w3,
                SUM(COALESCE(md.deduction_w3, 0) * modules.price) as deduction_w3,
                SUM(COALESCE(ma.addition_w4, 0) * modules.price) as addition_w4,
                SUM(COALESCE(md.deduction_w4, 0) * modules.price) as deduction_w4,
                SUM(COALESCE(ma.addition_w5, 0) * modules.price) as addition_w5,
                SUM(COALESCE(md.deduction_w5, 0) * modules.price) as deduction_w5,
                SUM(COALESCE(ma.total_addition, 0) * modules.price) as total_addition,
                SUM(COALESCE(md.total_deduction, 0) * modules.price) as total_deduction,
                SUM(({$totalStockQry}) * modules.price) as total_balance,
                SUM(COALESCE(mi.initial_stock, 0) * modules.price) as initial_balance,
                SUM(({$endingStockQry}) * modules.price) as ending_balance,
                SUM(COALESCE(mo.opname, 0) * modules.price) as opname_balance,
                SUM({$endingStockQry} = 0) as out_of_stock,
                SUM({$endingStockQry} < modules.min_stock) as less_than_min_stock,
                SUM({$endingStockQry} != COALESCE(mo.opname, 0)) as diff
            ")
            ->leftJoinSub($moduleOpnameQry, 'mo', 'mo.module_id', '=', 'modules.id')
            ->leftJoinSub($moduleAdditionQry, 'ma', 'ma.module_id', '=', 'modules.id')
            ->leftJoinSub($moduleDeductionQry, 'md', 'md.module_id', '=', 'modules.id')
            ->leftJoinSub($lastMonthModuleTransactionQry, 'mi', 'mi.module_id', '=', 'modules.id')
            ->where('type', '!=', 'ATK')
            ->first();

        return $stats;
    }
}
