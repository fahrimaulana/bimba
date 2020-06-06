<?php

namespace App\Http\Controllers\Client\Progressive;

use PDF;
use Carbon\Carbon;
use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Models\Staff\StaffIncome;
use App\Http\Controllers\Controller;
use App\Models\Staff\StaffAllowance;
use App\Models\Staff\StaffDeduction;
use App\Models\Master\PositionSalary;
use App\Models\Master\ProgressiveValue;
use App\Models\Master\SpecialAllowanceGroup;

class SlipController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-slip-progressive');

        $staffs      = Staff::get();
        $departments = Department::get();

        return view('client.progressive.slip.index', compact('staffs', 'departments'));
    }

    private function data($id)
    {
        checkPermissionTo('view-slip-progressive');

        $valueFM = 1.17;
        $commission = 50000;
        $commissionAsKU = 800000;
        $results = [];

        $key = Staff::findOrFail($id);

        $result['id']           = $key->id;
        $result['name']           = $key->name;
        $result['nik']            = $key->nik;
        $result['position']       = optional($key->position)->name;
        $result['status']         = $key->status;
        $result['department']     = optional($key->department)->name;
        $result['joined_date']    = yearMonthFormat($key->joined_date);
        $result['account_bank']   = $key->account_bank;
        $result['account_number'] = $key->account_number;
        $result['account_name']   = $key->account_name;
        $result['month_paid']     = now()->format('M Y');

        $result['student_data']['active'] = Student::where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->count();

        $result['student_data']['active_paid'] = Student::selectRaw("
                                                            SUM(CASE WHEN t.is_paid = 1 THEN 1 ELSE 0 END) as paid_count
                                                        ")
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->leftJoin('master_student_notes as msn', function ($qry) {
                                                            $qry->on('msn.id', 'students.note_id')
                                                                ->whereNull('msn.deleted_at');
                                                        })
                                                        ->leftJoin('master_classes as mc', function ($qry) {
                                                            $qry->on('mc.id', 'students.class_id')
                                                                ->whereNull('mc.deleted_at');
                                                        })
                                                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                                                            $qry->on('mcp.grade_id', 'students.grade_id')
                                                                ->on('mcp.class_id', 'mc.id')
                                                                ->whereNull('mcp.deleted_at');
                                                        })
                                                        ->leftJoin('tuitions as t', function ($qry) {
                                                            $qry->on('t.student_id', '=', 'students.id')
                                                                ->where('t.year', year())
                                                                ->where('t.month', month())
                                                                ->whereNull('t.deleted_at');
                                                        })
                                                        ->where('mc.scholarship', '!=', 'Dhuafa')
                                                        ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                                        ->first();

        $result['student_data']['warranty'] = Student::withoutGlobalScope('active')
                                                        ->leftJoin('master_student_notes', 'master_student_notes.id', '=', 'students.note_id')
                                                        ->where('master_student_notes.name', 'Garansi')
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->count();

        $result['student_data']['dhuafa'] = Student::withoutGlobalScope('active')
                                                        ->leftJoin('master_classes', 'master_classes.id', '=', 'students.class_id')
                                                        ->where('master_classes.scholarship', 'Dhuafa')
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->count();

        $result['student_data']['bnf'] = Student::withoutGlobalScope('active')
                                                        ->leftJoin('master_classes', 'master_classes.id', '=', 'students.class_id')
                                                        ->where('master_classes.scholarship', 'BNF')
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->count();

        $result['student_data']['bnf_paid'] = Student::withoutGlobalScope('active')
                                                        ->selectRaw("
                                                            SUM(CASE WHEN t.is_paid = 1 THEN 1 ELSE 0 END) as paid_count
                                                        ")
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->leftJoin('master_student_notes as msn', function ($qry) {
                                                            $qry->on('msn.id', 'students.note_id')
                                                                ->whereNull('msn.deleted_at');
                                                        })
                                                        ->leftJoin('master_classes as mc', function ($qry) {
                                                            $qry->on('mc.id', 'students.class_id')
                                                                ->whereNull('mc.deleted_at');
                                                        })
                                                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                                                            $qry->on('mcp.grade_id', 'students.grade_id')
                                                                ->on('mcp.class_id', 'mc.id')
                                                                ->whereNull('mcp.deleted_at');
                                                        })
                                                        ->leftJoin('tuitions as t', function ($qry) {
                                                            $qry->on('t.student_id', '=', 'students.id')
                                                                ->where('t.year', year())
                                                                ->where('t.month', month())
                                                                ->whereNull('t.deleted_at');
                                                        })
                                                        ->where('mc.scholarship', 'BNF')
                                                        ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                                        ->first();

        $result['money_order']['bnf'] = Student::withoutGlobalScope('active')
                                                        ->selectRaw("
                                                            SUM(CASE WHEN t.is_paid = 1 THEN mcp.price + nim ELSE 0 END) as paid_total
                                                        ")
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->leftJoin('master_student_notes as msn', function ($qry) {
                                                            $qry->on('msn.id', 'students.note_id')
                                                                ->whereNull('msn.deleted_at');
                                                        })
                                                        ->leftJoin('master_classes as mc', function ($qry) {
                                                            $qry->on('mc.id', 'students.class_id')
                                                                ->whereNull('mc.deleted_at');
                                                        })
                                                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                                                            $qry->on('mcp.grade_id', 'students.grade_id')
                                                                ->on('mcp.class_id', 'mc.id')
                                                                ->whereNull('mcp.deleted_at');
                                                        })
                                                        ->leftJoin('tuitions as t', function ($qry) {
                                                            $qry->on('t.student_id', '=', 'students.id')
                                                                ->where('t.year', year())
                                                                ->where('t.month', month())
                                                                ->whereNull('t.deleted_at');
                                                        })
                                                        ->where('mc.scholarship', 'BNF')
                                                        ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                                        ->first();

        $result['fm']['total'] = 0;
        $result['commission']['asku'] = 0;
        $result['commission']['total'] = 0;
        foreach (Department::cursor() as $value) {
            $result['student_data']['department_'.$value->id]['mb'] = Student::whereDepartmentId($value->id)
                                                                    ->whereTeacherId($key->id)
                                                                    ->count();
            $result['student_data']['department_'.$value->id]['mt'] = Student::withoutGlobalScope('active')
                                                                    ->whereDepartmentId($value->id)
                                                                    ->whereTrialTeacherId($key->id)
                                                                    ->whereStatus('Trial')
                                                                    ->count();

            $result['money_order']['department_'.$value->id] = Student::selectRaw("
                                                            SUM(CASE WHEN t.is_paid = 1 THEN mcp.price + nim ELSE 0 END) as paid_total
                                                        ")
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->where('students.department_id', $value->id)
                                                        ->leftJoin('master_student_notes as msn', function ($qry) {
                                                            $qry->on('msn.id', 'students.note_id')
                                                                ->whereNull('msn.deleted_at');
                                                        })
                                                        ->leftJoin('master_classes as mc', function ($qry) {
                                                            $qry->on('mc.id', 'students.class_id')
                                                                ->whereNull('mc.deleted_at');
                                                        })
                                                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                                                            $qry->on('mcp.grade_id', 'students.grade_id')
                                                                ->on('mcp.class_id', 'mc.id')
                                                                ->whereNull('mcp.deleted_at');
                                                        })
                                                        ->leftJoin('tuitions as t', function ($qry) {
                                                            $qry->on('t.student_id', '=', 'students.id')
                                                                ->where('t.year', year())
                                                                ->where('t.month', month())
                                                                ->whereNull('t.deleted_at');
                                                        })
                                                        ->where('mc.scholarship', '!=', 'Dhuafa')
                                                        ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                                        ->first();

            foreach (Grade::cursor() as $grade) {
                $result['money_order']['department_'.$value->id]['grade_'.$grade->id] = Student::selectRaw("
                                                            SUM(CASE WHEN t.is_paid = 1 THEN mcp.price + nim ELSE 0 END) as paid_total
                                                        ")
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->where('students.department_id', $value->id)
                                                        ->where('students.grade_id', $grade->id)
                                                        ->leftJoin('master_student_notes as msn', function ($qry) {
                                                            $qry->on('msn.id', 'students.note_id')
                                                                ->whereNull('msn.deleted_at');
                                                        })
                                                        ->leftJoin('master_classes as mc', function ($qry) {
                                                            $qry->on('mc.id', 'students.class_id')
                                                                ->whereNull('mc.deleted_at');
                                                        })
                                                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                                                            $qry->on('mcp.grade_id', 'students.grade_id')
                                                                ->on('mcp.class_id', 'mc.id')
                                                                ->whereNull('mcp.deleted_at');
                                                        })
                                                        ->leftJoin('tuitions as t', function ($qry) {
                                                            $qry->on('t.student_id', '=', 'students.id')
                                                                ->where('t.year', year())
                                                                ->where('t.month', month())
                                                                ->whereNull('t.deleted_at');
                                                        })
                                                        ->where('mc.scholarship', '!=', 'Dhuafa')
                                                        ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                                        ->first();

                $result['price_value']['department_'.$value->id]['grade_'.$grade->id] = Student::select('mcp.price as price')
                                                        ->where(function ($qry) use ($key) {
                                                            $qry->whereTrialTeacherId($key->id)
                                                                ->orWhere('teacher_id', $key->id);
                                                        })
                                                        ->where('students.department_id', $value->id)
                                                        ->where('students.grade_id', $grade->id)
                                                        ->leftJoin('master_student_notes as msn', function ($qry) {
                                                            $qry->on('msn.id', 'students.note_id')
                                                                ->whereNull('msn.deleted_at');
                                                        })
                                                        ->leftJoin('master_classes as mc', function ($qry) {
                                                            $qry->on('mc.id', 'students.class_id')
                                                                ->whereNull('mc.deleted_at');
                                                        })
                                                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                                                            $qry->on('mcp.grade_id', 'students.grade_id')
                                                                ->on('mcp.class_id', 'mc.id')
                                                                ->whereNull('mcp.deleted_at');
                                                        })
                                                        ->leftJoin('tuitions as t', function ($qry) {
                                                            $qry->on('t.student_id', '=', 'students.id')
                                                                ->where('t.year', year())
                                                                ->where('t.month', month())
                                                                ->whereNull('t.deleted_at');
                                                        })
                                                        ->where('mc.scholarship', '!=', 'Dhuafa')
                                                        ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
                                                        ->first();

                if ($value->id == 1) {
                    if (!is_null($result['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total']) && !is_null($result['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']) && $result['price_value']['department_'.$value->id]['grade_'.$grade->id]['price'] != 0) {
                        $result['fm']['department_'.$value->id]['grade_'.$grade->id] = round(($result['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total'] / $result['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']) * $valueFM);
                    } else {
                        $result['fm']['department_'.$value->id]['grade_'.$grade->id] = 0;
                    }
                } else {
                    if (!is_null($result['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total']) && !is_null($result['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']) && $result['price_value']['department_'.$value->id]['grade_'.$grade->id]['price'] != 0) {
                        $result['fm']['department_'.$value->id]['grade_'.$grade->id] = round($result['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total'] / $result['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']);
                    } else {
                        $result['fm']['department_'.$value->id]['grade_'.$grade->id] = 0;
                    }
                }

                $result['fm']['total'] += $result['fm']['department_'.$value->id]['grade_'.$grade->id];
            }

            $result['commission']['department_'.$value->id]['mb'] = $result['student_data']['department_'.$value->id]['mb'] * $commission;
            $result['commission']['department_'.$value->id]['mt'] = $result['student_data']['department_'.$value->id]['mt'] * $commission;
            $result['commission']['total'] = $result['commission']['total'] + $result['commission']['department_'.$value->id]['mb'] + $result['commission']['department_'.$value->id]['mt'];
        }

        $result['fm']['dhuafa_bnf_warranty'] = $result['student_data']['dhuafa'] + $result['student_data']['bnf'] + $result['student_data']['warranty'];
        $result['fm']['total'] += $result['fm']['dhuafa_bnf_warranty'];

        if (optional($key->position)->name == 'Asisten KU') {
            $result['commission']['asku'] = $result['commission']['asku'] + $commissionAsKU;
            $result['commission']['total'] = $result['commission']['total'] + $commissionAsKU;
        }

        $result['progressive'] = optional(ProgressiveValue::wherePositionId($key->position_id)->where('start_fm', '<=', $result['fm']['total'])->where('end_fm', '>=', $result['fm']['total'])->first())->rates;
        $result['paid_out'] = $result['progressive'] + $result['commission']['total'];

        return $result;
    }

    private function studentData($id)
    {
        checkPermissionTo('view-slip-progressive');

        $result = Student::withoutGlobalScope('active')
                        ->selectRaw('students.nim, students.name, md.name as department, mc.code, mg.name as grade, CASE WHEN t.is_paid = 1 THEN mcp.price + nim ELSE 0 END as spp, students.status, msn.name as note')
                        ->where(function ($qry) use ($id) {
                            $qry->whereTrialTeacherId($id)
                                ->orWhere('teacher_id', $id);
                        })
                        ->leftJoin('master_departments as md', function ($qry) {
                            $qry->on('md.id', 'students.department_id')
                                ->whereNull('md.deleted_at');
                        })
                        ->leftJoin('master_classes as mc', function ($qry) {
                            $qry->on('mc.id', 'students.class_id')
                                ->whereNull('mc.deleted_at');
                        })
                        ->leftJoin('master_grades as mg', function ($qry) {
                            $qry->on('mg.id', 'students.grade_id')
                                ->whereNull('mg.deleted_at');
                        })
                        ->leftJoin('master_student_notes as msn', function ($qry) {
                            $qry->on('msn.id', 'students.note_id')
                                ->whereNull('msn.deleted_at');
                        })
                        ->leftJoin('master_class_prices as mcp', function ($qry) {
                            $qry->on('mcp.grade_id', 'students.grade_id')
                                ->on('mcp.class_id', 'mc.id')
                                ->whereNull('mcp.deleted_at');
                        })
                        ->leftJoin('tuitions as t', function ($qry) {
                            $qry->on('t.student_id', '=', 'students.id')
                                ->where('t.year', year())
                                ->where('t.month', month())
                                ->whereNull('t.deleted_at');
                        })
                        ->get()
                        ->toArray();

        return $result;
    }

    public function getData(Request $request)
    {
        checkPermissionTo('view-slip-progressive');

        $result                     = $this->data($request->id);
        $result['data']['students'] = $this->studentData($request->id);

        return response()->json([
            'status'  => 'success',
            'message' => null,
            'data'    => $result
        ]);
    }

    public function print($id)
    {
        checkPermissionTo('view-slip-progressive');

        $departments = Department::get();
        $result      = $this->data($id);
        $students    = $this->studentData($id);

        $pdf = PDF::loadView('client.progressive.slip.print', compact(['departments', 'result', 'students']));
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
