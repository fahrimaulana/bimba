<?php

namespace App\Http\Controllers\Client\Salary;

use PDF;
use Datatables;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Staff\StaffIncome;
use App\Models\Staff\StaffAbsence;
use App\Http\Controllers\Controller;
use App\Models\Staff\StaffDeduction;
use App\Models\Master\SpecialAllowanceGroup;

class SlipController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-staff-slip-list');

        return view('client.salary.slip.index');
    }

    public function getData()
    {
        checkPermissionTo('view-staff-slip-list');

        $staffs = Staff::with([
            'department' => function ($qry) {
                $qry->withoutGlobalScopes();
            },
            'position'  => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])->select('staff.*');

        return Datatables::of($staffs)
            ->editColumn('joined_date', function ($staff) {
                return optional($staff->joined_date)->format('d M Y');
            })
            ->addColumn('age', function ($staff) {
                return $staff->birth_date->diffInYears(now()) . ' Years';
            })
            ->editColumn('status', function ($staff) {
                if ($staff->status == 'Active')
                    return '<span class="tag tag-primary tl-tip">Aktif</span>';
                elseif ($staff->status == 'Intern')
                    return '<span class="tag tag-warning tl-tip">Magang</span>';
                elseif ($staff->status == 'Resign')
                    return '<span class="tag tag-danger tl-tip">Resign</span>';
            })
            ->editColumn('active_work', function ($staff) {
                return yearMonthFormat($staff->joined_date);
            })
            ->addColumn('action', function ($staff) {
                $print = '<a class="no-decor text-success btn btn-icon tl-tip btn-sm" data-original-title="Print Slip Salary" data-url="'.route('client.salary.slip.print', $staff->id).'" data-toggle="modal" data-target="#reprint-receipt-modal"><i class="icon wb-print" aria-hidden="true"></i></a>' ;

                return (userCan('print-salary-slip') ? $print : '');
            })
            ->rawColumns(['joined_date', 'age',  'status', 'action', 'active_work'])
            ->make(true);
    }

    public function print($id)
    {
        $staff = Staff::selectRaw("
                staff.*,
                SUM(CASE WHEN mar.status = 'Sakit' THEN 1 ELSE 0 END) as sick_total,
                SUM(CASE WHEN mar.status = 'Izin' THEN 1 ELSE 0 END) as leave_total,
                SUM(CASE WHEN mar.status = 'Alpa' THEN 1 ELSE 0 END) as alpha_total,
                SUM(CASE WHEN mar.status = 'Tidak Aktif' THEN 1 ELSE 0 END) as not_active_total,
                SUM(CASE WHEN mar.status = 'C' THEN 1 else 0 END) as furlough_total
            ")
            ->with(['department','position'])
            ->leftJoin('staff_absences as sa', function ($qry) {
                $year = year();
                $month = month();
                $prevMonth = $month - 1;
                $qry->on('sa.staff_id', '=', 'staff.id')
                    ->whereBetween('sa.absent_date', ["{$year}-{$prevMonth}-26", "{$year}-{$month}-25"]);
            })
            ->leftJoin('master_absence_reasons as mar', 'mar.id', '=', 'sa.absence_reason_id')
            ->where('staff.id', $id)
            ->groupBy(getTableColumns('staff'))
            ->first();

        $salaryIncome = StaffIncome::whereHas('staff')->where('staff_id', $id)->first();

        $allowanceGroups = SpecialAllowanceGroup::
                        selectRaw("
                            master_special_allowance_groups.name,
                            CASE WHEN master_special_allowance_groups.id
                                THEN IFNULL((SELECT amount FROM staff_allowances WHERE allowance_group_id = master_special_allowance_groups.id and staff_allowances.income_id= ?),0)
                                ELSE 0
                            END amount
                        ", [optional($salaryIncome)->id])->get();

        $salaryDeduction = StaffDeduction::whereHas('staff')->where('staff_id', $id)->first();

        $slipMonth = date_format(Carbon::createFromDate(year(), month()), 'M Y');
        $pdf = PDF::loadView('client.salary.slip.print', compact(['staff', 'salaryIncome', 'salaryDeduction', 'allowanceGroups', 'slipMonth']));
        $pdf->setOptions([
            'margin-top' => 0,
            'margin-right' => 3,
            'margin-bottom' => 0,
            'margin-left' => 3,
        ]);


        $pdf->setPaper('a5', 'landscape');

        return $pdf->inline();
    }
}
