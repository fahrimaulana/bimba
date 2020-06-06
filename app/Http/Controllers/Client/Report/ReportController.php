<?php

namespace App\Http\Controllers\Client\Report;

use PDF;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Product;
use App\Models\Tuition;
use Datatables, Schema;
use App\Models\Preference;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Module\Module;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Models\Staff\StaffIncome;
use App\Enum\Transaction\ExtraSize;
use App\Models\PettyCash\PettyCash;
use App\Http\Controllers\Controller;
use App\Models\Master\StaffPosition;
use App\Models\Staff\StaffAllowance;
use App\Models\Staff\StaffDeduction;
use App\Models\Student\TrialStudent;
use App\Models\Master\PositionSalary;
use App\Models\Transaction\Transaction;
use App\Models\Master\PettyCashCategory;
use App\Models\Module\ModuleTransaction;
use App\Models\Master\SpecialAllowanceGroup;
use App\Models\Transaction\TransactionDetail;
use App\Http\Controllers\Client\Salary\SalaryController;
use App\Http\Controllers\Client\Progressive\RecapController;
use App\Http\Controllers\Client\Order\OrderStatisticController;
use App\Http\Controllers\Client\Tuition\TuitionReportController;
use App\Http\Controllers\Client\Module\ModuleStatisticController;

class ReportController extends Controller
{
    public function index()
    {
        return view('client.report.index');
    }

    public function viewPdf($from = null, $to = null)
    {
        $year  = year();
        $month = month();

        $result            = [];
        $result['staff']   = $this->staffData();
        $result['student'] = $this->studentData($month, $year);
        $result['finance'] = $this->financeData($month, $year);
        $result['module']  = $this->moduleData();
        $result['order']   = $this->orderData($month, $year);

        $pdf = PDF::loadView('client.report.view-pdf', compact('result'));

        $pdf->setOptions([
            'margin-top' => 4,
            'margin-right' => 3,
            'margin-bottom' => 1,
            'margin-left' => 3,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->inline();
    }

    private function staffData()
    {
        $result['others'] = 0;
        $result['total']  = 0;
        foreach (StaffPosition::all() as $staffPosition) {
            $staffPositionName = str_replace(' ', '_', strtolower($staffPosition->name));
            $result[$staffPositionName] = Staff::wherePositionId($staffPosition->id)->count();

            if ($staffPositionName != 'kepala_unit' && $staffPositionName != 'asisten_ku' && $staffPositionName != 'guru' && $staffPositionName != 'asisten_guru' && $staffPositionName != 'staff_mobile') {
                $result['others'] += $result[$staffPositionName];
            }
            $result['total'] += $result[$staffPositionName];
        }

        return $result;
    }

    private function studentData($month, $year)
    {

        $result['new_trial'] = Student::withoutGlobalScope('active')
                                                ->whereYear('joined_date', $year)
                                                ->whereMonth('joined_date', $month)
                                                ->whereStatus('Trial')
                                                ->count();

        $firstStudent = Student::withoutGlobalScope('active')->orderBy('joined_date', 'asc')->first();

        $result['active_last_month'] = Student::whereBetween('joined_date', [optional($firstStudent)->joined_date, new Carbon('last day of last month')])->count();

        $result['new_active'] = Student::whereYear('joined_date', $year)
                                                ->whereMonth('joined_date', $month)
                                                ->count();

        $result['out'] = Student::withoutGlobalScope('active')
                                            ->whereYear('joined_date', $year)
                                            ->whereMonth('joined_date', $month)
                                            ->whereStatus('Out')
                                            ->count();
        $result['active_current_month'] = Student::whereYear('joined_date', $year)
                                                            ->whereMonth('joined_date', $month)
                                                            ->count();
        $result['dhuafa'] = Student::whereYear('joined_date', $year)
                                            ->whereMonth('joined_date', $month)
                                            ->leftJoin('master_classes', 'master_classes.id', '=', 'students.class_id')
                                            ->where('master_classes.scholarship', 'Dhuafa')
                                            ->count();
        $result['bnf'] = Student::whereYear('joined_date', $year)
                                            ->whereMonth('joined_date', $month)
                                            ->leftJoin('master_classes', 'master_classes.id', '=', 'students.class_id')
                                            ->where('master_classes.scholarship', 'BNF')
                                            ->count();

        return $result;
    }

    private function financeData($month, $year)
    {
        $result['transaction'] = Transaction::whereYear('date', $year)
                                                        ->whereMonth('date', $month)
                                                        ->sum('total');
        $result['petty_cash'] = PettyCash::selectRaw("IFNULL(SUM(IFNULL(debit, 0) - IFNULL(credit, 0)), 0) as saldo")->first()->saldo;
        $result['spending'] = PettyCash::selectRaw("SUM(IFNULL(credit, 0)) as total_spending")->first()->total_spending;
        $result['tuition'] = (new TuitionReportController)->getStudentWithTuitionQuery()
                                    ->selectRaw("
                                        SUM(CASE WHEN t.is_paid = 1 THEN mcp.price + nim ELSE 0 END) as paid_total
                                    ")
                                    ->whereNotIn('students.status', ['Out', 'Trial'])
                                    ->where('mc.scholarship', '!=', 'Dhuafa')
                                    ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                    ->first()
                                    ->paid_total;

        $staffIncome = StaffIncome::selectRaw('staff_incomes.*, COALESCE(SUM(sa.amount), 0) as allowance')
            ->leftJoin('staff_allowances as sa', 'income_id', '=', 'staff_incomes.id')
            ->groupBy(getTableColumns('staff_incomes'));
        $staff = Staff::selectRaw("
                staff.*,
                COALESCE(SUM(si.basic_salary + si.daily + si.functional + si.health + si.underpayment + si.other + si.allowance), 0) as income,
                COALESCE(SUM(sd.sick + sd.leave + sd.alpha + sd.not_active + sd.overpayment + sd.other), 0) as deduction
            ")
            ->leftJoin('staff_deductions as sd', function ($qry) {
                $qry->on('sd.staff_id', '=', 'staff.id')
                    ->whereYear('sd.created_at', year())
                    ->whereMonth('sd.created_at', month());
            })
            ->leftJoinSub($staffIncome, 'si', 'si.staff_id', '=', 'staff.id')
            ->groupBy(getTableColumns('staff'))
            ->get();
        $result['benefit'] = 0;
        foreach ($staff as $s) {
            $payment = $s->income - $s->deduction;
            $result['benefit'] += $payment;
        }

        $progressiveData = (new RecapController)->data();
        $result['progressive'] = 0;
        foreach ($progressiveData as $key) {
            $result['progressive'] += $key['progressive'] != null ? $key['progressive'] : 0;
        }
        $departments = Department::selectRaw("
                SUM(CASE WHEN s.status != 'Out' AND mc.scholarship != 'Dhuafa' AND (msn.name != 'Garansi' OR msn.id IS NULL) THEN IFNULL(mcp.price, 0) + s.nim ELSE 0 END) as total_tuition
            ")
            ->leftJoin('students as s', function ($qry) {
                $qry->on('s.department_id', 'master_departments.id')
                    ->where('s.status', '!=', 'Trial');
            })
            ->leftJoin('master_classes as mc', 'mc.id', '=', 's.class_id')
            ->leftJoin('master_student_notes as msn', 'msn.id', '=', 's.note_id')
            ->leftJoin('master_class_prices as mcp', function ($qry) {
                $qry->on('mcp.grade_id', 's.grade_id')
                    ->on('mcp.class_id', 'mc.id');
            })
            ->whereNull('master_departments.deleted_at')
            ->whereNull('s.deleted_at')
            ->where('master_departments.client_id', clientId())
            ->groupBy('master_departments.name')
            ->get();
        $totalTuition = 0;
        foreach ($departments as $department) {
            $totalTuition += $department->total_tuition != null ? $department->total_tuition : 0;
        }
        $profitSharingPercentage = Preference::valueOf('profit_sharing_percentage');
        $result['profit_sharing'] = $totalTuition * $profitSharingPercentage / 100;

        return $result;
    }

    private function moduleData()
    {
        $moduleData = (new ModuleStatisticController)->data();
        $result['initial_balance']     = $moduleData->initial_balance;
        $result['total_addition']      = $moduleData->total_addition;
        $result['total_deduction']     = $moduleData->total_deduction;
        $result['ending_balance']      = $moduleData->ending_balance;
        $result['opname_balance']      = $moduleData->opname_balance;
        $result['less_than_min_stock'] = $moduleData->less_than_min_stock;
        $result['diff']                = $moduleData->diff;

        return $result;
    }

    public function orderData($month, $year)
    {
        $weekSelects = collect();
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
        $result['module'] = 0;
        foreach (range(1, 5) as $week) {
            $result['module'] += $moduleTotal[$week];
        }

        $orderTransactionData = (new OrderStatisticController)->transactionData();
        $kaCount = $meCount = $bagCount = $result['certificate'] = $result['stpb'] = 0;
        foreach ($orderTransactionData as $orderTransaction) {
            $kaCount += $orderTransaction->ka_count;
            $meCount += $orderTransaction->me_count;
            $bagCount += $orderTransaction->tas_count;
            $result['certificate'] += $orderTransaction->certificate_total;
            $result['stpb'] += $orderTransaction->stpb_total;
        }
        $products = Product::all();
        $result['ex_module'] = $meCount * optional($products->where('code', 'ME')->first())->price;
        $result['bag'] = $bagCount * $products->where('code', 'TAS')->first()->price;
        $result['ka'] = $kaCount * $products->where('code', 'KA')->first()->price;
        $orderAtkStatsData = (new OrderStatisticController)->atkStatsData();
        $result['atk'] = $orderAtkStatsData->total;

        return $result;
    }
}

