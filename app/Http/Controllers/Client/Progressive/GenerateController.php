<?php

namespace App\Http\Controllers\Client\Progressive;

use Illuminate\Http\Request;
use App\Models\Staff\StaffIncome;
use App\Http\Controllers\Controller;

class GenerateController extends Controller
{
    public function process()
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
