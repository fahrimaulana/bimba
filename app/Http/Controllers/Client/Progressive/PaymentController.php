<?php

namespace App\Http\Controllers\Client\Progressive;

use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Models\Staff\StaffIncome;
use App\Http\Controllers\Controller;
use App\Models\Staff\StaffAllowance;
use App\Models\Staff\StaffDeduction;
use App\Models\Master\PositionSalary;
use App\Models\Master\SpecialAllowanceGroup;

class PaymentController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-payment-progressive');

        $staffIncome = StaffIncome::selectRaw(
                'staff_incomes.*'
            )
            ->leftJoin('staff_allowances as sa', 'income_id', '=', 'staff_incomes.id')
            ->groupBy(getTableColumns('staff_incomes'));

        $staff = Staff::selectRaw("
                staff.*,
                COALESCE(SUM(si.basic_salary + si.daily + si.functional + si.health), 0) as thp
            ")
            ->leftJoinSub($staffIncome, 'si', 'si.staff_id', '=', 'staff.id')
            ->groupBy(getTableColumns('staff'))
            ->get();

        return view('client.progressive.payment.index', compact('staff'));
    }
}
