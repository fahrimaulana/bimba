<?php

namespace App\Http\Controllers\Client\Finance;

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
use App\Http\Controllers\Client\Report\ReportController as MainReport;
use App\Http\Controllers\Client\Progressive\RecapController;

class ReportController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-report-finance');

        $year = year();
        $month = month();

        $progressiveRecapData = (new RecapController)->data();
        foreach (Department::cursor() as $department) {
            $result['tuition']['department_'.$department->id] = 0;
        }

        $result['progressive'] = $result['tuition']['total'] = 0;
        foreach ($progressiveRecapData as $key) {
            foreach (Department::cursor() as $department) {
                $result['tuition']['department_'.$department->id] += $key['money_order']['department_'.$department->id]['paid_total'];
                $result['tuition']['total'] += $key['money_order']['department_'.$department->id]['paid_total'];
            }
            $result['progressive'] += $key['progressive'] != null ? $key['progressive'] : 0;
        }
        $result['transaction_registration'] = Transaction::selectRaw('transaction_details.total')
                                            ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                                            ->where('transaction_details.category', '=', 'Registration')
                                            ->whereYear('date', $year)
                                            ->whereMonth('date', $month)->first();

        $result['transaction_registration'] = (optional($result['transaction_registration'])->total) ? optional($result['transaction_registration'])->total : '0';
        $result['transaction'] = Transaction::whereYear('date', $year)
                                            ->whereMonth('date', $month)
                                            ->sum('total');
        $orderData = (new MainReport)->orderData($month, $year);
        $result['sales_transaction'] = $orderData['module'] + $orderData['stpb'] + $orderData['certificate'] + $orderData['ex_module'] + $orderData['bag'] + $orderData['ka'] + $orderData['atk'];
        $result['total_transaction'] = $result['transaction'] + $result['tuition']['total'] + $result['sales_transaction'];

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
        $result['staff_salary'] = 0;
        foreach ($staff as $s) {
            $payment = $s->income - $s->deduction;
            $result['staff_salary'] += $payment;
        }

        $result['petty_cash'] = $this->pettyCashData($month, $year);
        $result['profit_sharing'] = $this->profitSharingData();
        return view('client.finance.report.index', compact('result'));
    }

    public function viewPdf($from = null, $to = null)
    {
        checkPermissionTo('view-report-finance');

        $year = year();
        $month = month();

        $progressiveRecapData = (new RecapController)->data();
        foreach (Department::cursor() as $department) {
            $result['tuition']['department_'.$department->id] = 0;
        }

        $result['progressive'] = $result['tuition']['total'] = 0;
        foreach ($progressiveRecapData as $key) {
            foreach (Department::cursor() as $department) {
                $result['tuition']['department_'.$department->id] += $key['money_order']['department_'.$department->id]['paid_total'];
                $result['tuition']['total'] += $key['money_order']['department_'.$department->id]['paid_total'];
            }
            $result['progressive'] += $key['progressive'] != null ? $key['progressive'] : 0;
        }
        $result['transaction_registration'] = Transaction::selectRaw('transaction_details.total')
                                            ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                                            ->where('transaction_details.category', '=', 'Registration')
                                            ->whereYear('date', $year)
                                            ->whereMonth('date', $month)->first();

        $result['transaction_registration'] = (optional($result['transaction_registration'])->total) ? optional($result['transaction_registration'])->total : '0';
        $result['transaction'] = Transaction::whereYear('date', $year)
                                            ->whereMonth('date', $month)
                                            ->sum('total');
        $orderData = (new MainReport)->orderData($month, $year);
        $result['sales_transaction'] = $orderData['module'] + $orderData['stpb'] + $orderData['certificate'] + $orderData['ex_module'] + $orderData['bag'] + $orderData['ka'] + $orderData['atk'];
        $result['total_transaction'] = $result['transaction'] + $result['tuition']['total'] + $result['sales_transaction'];

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
        $result['staff_salary'] = 0;
        foreach ($staff as $s) {
            $payment = $s->income - $s->deduction;
            $result['staff_salary'] += $payment;
        }

        $result['petty_cash'] = $this->pettyCashData($month, $year);
        $result['profit_sharing'] = $this->profitSharingData();

        $pdf = PDF::loadView('client.finance.report.view-pdf', compact('result'));

        $pdf->setOptions([
            'margin-top' => 4,
            'margin-right' => 3,
            'margin-bottom' => 1,
            'margin-left' => 3,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->inline();

    }

    private function pettyCashData($month, $year)
    {
        $pettyCash = PettyCash::withoutGlobalScope('period')
                                ->selectRaw("IFNULL(SUM(IFNULL(debit, 0) - IFNULL(credit, 0)), 0) as saldo, SUM(debit) as debit, SUM(credit) as credit")
                                ->whereYear('date', '<=', $year)
                                ->whereMonth('date', '<', $month)
                                ->first();
        $result['initial_saldo'] = ($pettyCash->saldo) ? $pettyCash->saldo : '0';
        $result['debit'] = $pettyCash->debit ? $pettyCash->debit : '0';
        $result['credit'] = $pettyCash->credit ? $pettyCash->credit : '0';
        $result['final_saldo'] = ($result['initial_saldo'] + $result['debit']) - $result['credit'];

        return $result;
    }

    private function profitSharingData()
    {
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
        $profitSharingPercentage = Preference::valueOf('profit_sharing_percentage');
        $i = 0;
        foreach ($departments as $department) {
            $result['department_'.$i] = ($department->total_tuition != null ? $department->total_tuition : 0) * $profitSharingPercentage / 100;
            $i++;
        }

        return $result;
    }
}

