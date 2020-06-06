<?php

namespace App\Http\Controllers\Client\Progressive;

use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;
use App\Models\Master\ProgressiveValue;

class RecapController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-recap-progressive');

        $results     = $this->data();
        $departments = Department::get();
        $grades      = Grade::get();

        return view('client.progressive.recap.index', compact('results', 'departments', 'grades'));
    }

    public function data()
    {
        $i = 0;
        $valueFM = 1.17;
        $commission = 50000;
        $commissionAsKU = 800000;
        $results = [];
        foreach (Staff::cursor() as $key) {
            $results[$i]['name']           = $key->name;
            $results[$i]['nik']            = $key->nik;
            $results[$i]['position']       = optional($key->position)->name;
            $results[$i]['status']         = $key->status;
            $results[$i]['department']     = optional($key->department)->name;
            $results[$i]['joined_date']    = yearMonthFormat($key->joined_date);
            $results[$i]['account_bank']   = $key->account_bank;
            $results[$i]['account_number'] = $key->account_number;
            $results[$i]['account_name']   = $key->account_name;
            $results[$i]['month_paid']     = now()->format('M Y');

            $results[$i]['student_data']['active'] = Student::where(function ($qry) use ($key) {
                                                                $qry->whereTrialTeacherId($key->id)
                                                                    ->orWhere('teacher_id', $key->id);
                                                            })
                                                            ->count();

            $results[$i]['student_data']['active_paid'] = Student::selectRaw("
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

            $results[$i]['student_data']['warranty'] = Student::withoutGlobalScope('active')
                                                            ->leftJoin('master_student_notes', 'master_student_notes.id', '=', 'students.note_id')
                                                            ->where('master_student_notes.name', 'Garansi')
                                                            ->where(function ($qry) use ($key) {
                                                                $qry->whereTrialTeacherId($key->id)
                                                                    ->orWhere('teacher_id', $key->id);
                                                            })
                                                            ->count();

            $results[$i]['student_data']['dhuafa'] = Student::withoutGlobalScope('active')
                                                            ->leftJoin('master_classes', 'master_classes.id', '=', 'students.class_id')
                                                            ->where('master_classes.scholarship', 'Dhuafa')
                                                            ->where(function ($qry) use ($key) {
                                                                $qry->whereTrialTeacherId($key->id)
                                                                    ->orWhere('teacher_id', $key->id);
                                                            })
                                                            ->count();

            $results[$i]['student_data']['bnf'] = Student::withoutGlobalScope('active')
                                                            ->leftJoin('master_classes', 'master_classes.id', '=', 'students.class_id')
                                                            ->where('master_classes.scholarship', 'BNF')
                                                            ->where(function ($qry) use ($key) {
                                                                $qry->whereTrialTeacherId($key->id)
                                                                    ->orWhere('teacher_id', $key->id);
                                                            })
                                                            ->count();

            $results[$i]['student_data']['bnf_paid'] = Student::withoutGlobalScope('active')
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

            $results[$i]['money_order']['bnf'] = Student::withoutGlobalScope('active')
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

            $results[$i]['fm']['total'] = 0;
            $results[$i]['commission']['asku'] = 0;
            $results[$i]['commission']['total'] = 0;
            foreach (Department::cursor() as $value) {
                $results[$i]['student_data']['department_'.$value->id]['mb'] = Student::whereDepartmentId($value->id)
                                                                        ->whereTeacherId($key->id)
                                                                        ->count();
                $results[$i]['student_data']['department_'.$value->id]['mt'] = Student::withoutGlobalScope('active')
                                                                        ->whereDepartmentId($value->id)
                                                                        ->whereTrialTeacherId($key->id)
                                                                        ->whereStatus('Trial')
                                                                        ->count();

                $results[$i]['money_order']['department_'.$value->id] = Student::selectRaw("
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
                    $results[$i]['money_order']['department_'.$value->id]['grade_'.$grade->id] = Student::selectRaw("
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

                    $results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id] = Student::select('mcp.price as price')
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
                        if (!is_null($results[$i]['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total']) && !is_null($results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']) && $results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id]['price'] != 0) {
                            $results[$i]['fm']['department_'.$value->id]['grade_'.$grade->id] = round(($results[$i]['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total'] / $results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']) * $valueFM);
                        } else {
                            $results[$i]['fm']['department_'.$value->id]['grade_'.$grade->id] = 0;
                        }
                    } else {
                        if (!is_null($results[$i]['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total']) && !is_null($results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']) && $results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id]['price'] != 0) {
                            $results[$i]['fm']['department_'.$value->id]['grade_'.$grade->id] = round($results[$i]['money_order']['department_'.$value->id]['grade_'.$grade->id]['paid_total'] / $results[$i]['price_value']['department_'.$value->id]['grade_'.$grade->id]['price']);
                        } else {
                            $results[$i]['fm']['department_'.$value->id]['grade_'.$grade->id] = 0;
                        }
                    }

                    $results[$i]['fm']['total'] += $results[$i]['fm']['department_'.$value->id]['grade_'.$grade->id];
                }

                $results[$i]['commission']['department_'.$value->id]['mb'] = $results[$i]['student_data']['department_'.$value->id]['mb'] * $commission;
                $results[$i]['commission']['department_'.$value->id]['mt'] = $results[$i]['student_data']['department_'.$value->id]['mt'] * $commission;
                $results[$i]['commission']['total'] = $results[$i]['commission']['total'] + $results[$i]['commission']['department_'.$value->id]['mb'] + $results[$i]['commission']['department_'.$value->id]['mt'];
            }

            $results[$i]['fm']['dhuafa_bnf_warranty'] = $results[$i]['student_data']['dhuafa'] + $results[$i]['student_data']['bnf'] + $results[$i]['student_data']['warranty'];
            $results[$i]['fm']['total'] += $results[$i]['fm']['dhuafa_bnf_warranty'];

            if (optional($key->position)->name == 'Asisten KU') {
                $results[$i]['commission']['asku'] = $results[$i]['commission']['asku'] + $commissionAsKU;
                $results[$i]['commission']['total'] = $results[$i]['commission']['total'] + $commissionAsKU;
            }

            $results[$i]['progressive'] = optional(ProgressiveValue::wherePositionId($key->position_id)->where('start_fm', '<=', $results[$i]['fm']['total'])->where('end_fm', '>=', $results[$i]['fm']['total'])->first())->rates;
            $results[$i]['paid_out'] = $results[$i]['progressive'] + $results[$i]['commission']['total'];

            $i++;
        }

        return $results;
    }
}
