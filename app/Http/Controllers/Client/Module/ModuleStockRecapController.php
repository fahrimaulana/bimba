<?php

namespace App\Http\Controllers\Client\Module;

use DB, Exception;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Http\Controllers\Controller;
use App\Models\Module\ModuleTransaction;
use Illuminate\Validation\ValidationException;

class ModuleStockRecapController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-module-stock-recap-list');

        $moduleAdditionQry = ModuleTransaction::selectRaw("
                module_id,
                SUM(CASE WHEN week = 1 THEN qty ELSE 0 END) as addition_w1,
                SUM(CASE WHEN week = 2 THEN qty ELSE 0 END) as addition_w2,
                SUM(CASE WHEN week = 3 THEN qty ELSE 0 END) as addition_w3,
                SUM(CASE WHEN week = 4 THEN qty ELSE 0 END) as addition_w4,
                SUM(CASE WHEN week = 5 THEN qty ELSE 0 END) as addition_w5
            ")
            ->where('type', 'in')
            ->groupBy('module_id');

        $moduleDeductionQry = ModuleTransaction::selectRaw("
                module_id,
                SUM(CASE WHEN week = 1 THEN qty ELSE 0 END) as deduction_w1,
                SUM(CASE WHEN week = 2 THEN qty ELSE 0 END) as deduction_w2,
                SUM(CASE WHEN week = 3 THEN qty ELSE 0 END) as deduction_w3,
                SUM(CASE WHEN week = 4 THEN qty ELSE 0 END) as deduction_w4,
                SUM(CASE WHEN week = 5 THEN qty ELSE 0 END) as deduction_w5
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
            ->where('date', '<=', lockedDate())
            ->whereIn('type', ['in', 'out'])
            ->groupBy('module_id');

        $modules = Module::selectRaw('
                modules.id, modules.code, modules.min_stock,
                COALESCE(ma.addition_w1, 0) as addition_w1,
                COALESCE(md.deduction_w1, 0) as deduction_w1,
                COALESCE(ma.addition_w2, 0) as addition_w2,
                COALESCE(md.deduction_w2, 0) as deduction_w2,
                COALESCE(ma.addition_w3, 0) as addition_w3,
                COALESCE(md.deduction_w3, 0) as deduction_w3,
                COALESCE(ma.addition_w4, 0) as addition_w4,
                COALESCE(md.deduction_w4, 0) as deduction_w4,
                COALESCE(ma.addition_w5, 0) as addition_w5,
                COALESCE(md.deduction_w5, 0) as deduction_w5,
                COALESCE(mo.opname, 0) as opname,
                COALESCE(mi.initial_stock, 0) as initial_stock
            ')
            ->leftJoinSub($moduleOpnameQry, 'mo', 'mo.module_id', '=', 'modules.id')
            ->leftJoinSub($moduleAdditionQry, 'ma', 'ma.module_id', '=', 'modules.id')
            ->leftJoinSub($moduleDeductionQry, 'md', 'md.module_id', '=', 'modules.id')
            ->leftJoinSub($lastMonthModuleTransactionQry, 'mi', 'mi.module_id', '=', 'modules.id')
            ->where('type', '!=', 'ATK')
            ->get();

        return view('client.module.stock-recap.index', compact('modules'));
    }

    public function changeOpname(Request $request)
    {
        checkPermissionTo('change-module-stock-opname');

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'modules.*.opname' => 'required|integer'
            ]);

            ModuleTransaction::whereType('opname')
                ->whereYear('date', year())
                ->whereMonth('date', month())
                ->forceDelete();
            foreach ($request->input('modules', []) as $moduleId => $data) {
                if ($data['opname'] == 0) continue;

                $module = Module::find($moduleId);
                if (!$module) return validationError("Module #{$module->id} not found");

                $transaction = new ModuleTransaction;
                $transaction->client_id = clientId();
                $transaction->date = year() . "-" . month() . "-" . "01";
                $transaction->module_id = $module->id;
                $transaction->module_price = $module->price;
                $transaction->qty = $data["opname"];
                $transaction->type = "opname";
                $transaction->user_id = user()->id;
                $transaction->save();
            }
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return unknownError($e, 'Gagal menyimpan stok opname. Silakan coba lagi.');
        }
        DB::commit();

        return redirect()->back()->with('notif_success', 'Stock opname telah berhasil disimpan!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-module-usage');

        $transaction = ModuleTransaction::whereType('out')->findOrFail($id);
        $transaction->delete();

        return redirect()->route('client.module.usage.index')->with('notif_success', 'Pemakaian modul telah berhasil dihapus!');
    }
}
