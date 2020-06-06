<?php

namespace App\Http\Controllers\Client\Salary;

use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Staff\StaffIncome;
use App\Http\Controllers\Controller;
use App\Models\Staff\StaffAllowance;
use App\Models\Staff\StaffDeduction;
use App\Models\Master\PositionSalary;
use App\Models\Master\SpecialAllowanceGroup;

class SalaryController extends Controller
{
    public function incomeIndex()
    {
        checkPermissionTo('view-staff-income-list');

        $salaryIncomes = StaffIncome::whereHas('staff')->get();
        $allowanceGroups = SpecialAllowanceGroup::all();

        return view('client.salary.income.index', compact('salaryIncomes', 'allowanceGroups'));
    }

    public function incomeEdit($id)
    {
        checkPermissionTo('edit-staff-income');

        $income = StaffIncome::findOrFail($id);
        $allowanceGroups = SpecialAllowanceGroup::all();

        return view('client.salary.income.edit', compact('income', 'allowanceGroups'));
    }

    public function incomeUpdate($id, Request $request)
    {
        checkPermissionTo('edit-staff-income');

        $this->validate($request, [
            'other' => 'min:0',
            'underpayment' => 'min:0',
            'underpayment_month' => 'in:' . implode(',', range(1, 12)),
            'allowances.*' => 'min:0',
        ]);

        $income = StaffIncome::findOrFail($id);
        $income->underpayment = $request->underpayment;
        $income->underpayment_month = $request->underpayment_month;
        $income->other = $request->other;
        $income->save();

        foreach ($request->input('allowances', []) as $groupId => $amount) {
            $allowance = $income->allowances()->whereAllowanceGroupId($groupId)->first();
            if (!$allowance) $allowance = new StaffAllowance;
            if (optional($allowance->group)->department_id) continue;

            $allowance->income_id = $income->id;
            $allowance->allowance_group_id = $groupId;
            $allowance->amount = $amount;
            $allowance->save();
        }

        return redirect()->route('client.salary.income.index')->with('notif_success', 'Pendapatan staff telah berhasil di update.');
    }

    public function deductionIndex()
    {
        checkPermissionTo('view-staff-deduction-list');

        $salaryDeductions = StaffDeduction::whereHas('staff')->get();

        return view('client.salary.deduction.index', compact('salaryDeductions'));
    }

    public function deductionEdit($id)
    {
        checkPermissionTo('edit-staff-deduction');

        $deduction = StaffDeduction::findOrFail($id);

        return view('client.salary.deduction.edit', compact('deduction'));
    }

    public function deductionUpdate($id, Request $request)
    {
        checkPermissionTo('edit-staff-deduction');

        $this->validate($request, [
            'other' => 'min:0',
            'overpayment' => 'min:0',
            'overpayment_month' => 'in:' . implode(',', range(1, 12)),
        ]);

        $deduction = StaffDeduction::findOrFail($id);
        $deduction->overpayment = $request->overpayment;
        $deduction->overpayment_month = $request->overpayment_month;
        $deduction->other = $request->other;
        $deduction->save();

        return redirect()->route('client.salary.deduction.index')->with('notif_success', 'Potongan staff telah berhasil di update.');
    }

    public function paymentIndex()
    {
        checkPermissionTo('view-staff-salary-list');

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

        return view('client.salary.payment.index', compact('staff'));
    }

    public function generateStaffSalary()
    {
        checkPermissionTo('generate-staff-salary');

        if (StaffIncome::whereMonth('created_at', month())->whereYear('created_at', year())->first()) {
            return validationError("Gaji telah di-generate sebelumnya.");
        }

        $departmentBindedAllowanceGroups = SpecialAllowanceGroup::whereNotNull('department_id')->get();

        $staffArr = Staff::selectRaw("
                staff.*,
                SUM(CASE WHEN mar.status = 'Sakit' THEN 1 ELSE 0 END) as sick_total,
                SUM(CASE WHEN mar.status = 'Izin' THEN 1 ELSE 0 END) as leave_total,
                SUM(CASE WHEN mar.status = 'Alpa' THEN 1 ELSE 0 END) as alpha_total,
                SUM(CASE WHEN mar.status = 'Tidak Aktif' THEN 1 ELSE 0 END) as not_active_total
            ")
            ->leftJoin('staff_absences as sa', function ($qry) {
                $year = year();
                $month = month();
                $prevMonth = $month - 1;
                $qry->on('sa.staff_id', '=', 'staff.id')
                    ->whereBetween('sa.absent_date', ["{$year}-{$prevMonth}-26", "{$year}-{$month}-25"]);
            })
            ->leftJoin('master_absence_reasons as mar', 'mar.id', '=', 'sa.absence_reason_id')
            ->groupBy(getTableColumns('staff'))
            ->get();

        foreach ($staffArr as $staff) {
            $staffWorkLength = $staff->joined_date->diffInMonths(today());
            $positionSalary = PositionSalary::wherePositionId($staff->position_id)
                ->whereStatus($staff->status)
                ->whereRaw('? BETWEEN min_work_length and max_work_length', $staffWorkLength)
                ->first();

            $staffIncome = new StaffIncome;
            $staffIncome->client_id = clientId();
            $staffIncome->staff_id = $staff->id;
            $staffIncome->basic_salary = (float) optional($positionSalary)->basic_salary;
            $staffIncome->daily = (float) optional($positionSalary)->daily;
            $staffIncome->functional = (float) optional($positionSalary)->functional;
            $staffIncome->health = (float) optional($positionSalary)->health;
            $staffIncome->save();

            $thp = $staffIncome->basic_salary + $staffIncome->daily + $staffIncome->functional + $staffIncome->health;

            foreach ($departmentBindedAllowanceGroups as $allowanceGroup) {
                if ($allowanceGroup->department_id == $staff->department_id) {
                    $staffAllowance = new StaffAllowance;
                    $staffAllowance->income_id = $staffIncome->id;
                    $staffAllowance->allowance_group_id = $allowanceGroup->id;
                    $staffAllowance->amount = (float) optional($allowanceGroup->allowance)->price;
                    $staffAllowance->save();
                }
            }

            $sickDeduction = $staff->sick_total / 25 * $staffIncome->daily;
            $leaveDeduction = $staff->leave_total / 25 * $staffIncome->daily;
            $alphaDeduction = $staff->alpha_total / 25 * $thp;
            $notActiveDeduction = $staff->not_active_total / 25 * $thp;

            $staffDeduction = new StaffDeduction;
            $staffDeduction->client_id = clientId();
            $staffDeduction->staff_id = $staff->id;
            $staffDeduction->sick = $sickDeduction;
            $staffDeduction->leave = $leaveDeduction;
            $staffDeduction->alpha = $alphaDeduction;
            $staffDeduction->not_active = $notActiveDeduction;
            $staffDeduction->save();
        }

        return redirect()->back()->with('notif_success', 'Gaji telah berhasil di generate.');
    }
}
