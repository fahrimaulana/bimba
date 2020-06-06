<?php

namespace App\Http\Controllers\Client\Student;

use Datatables, PDF;
use App\Models\Student\Student;
use App\Http\Controllers\Controller;

class TuitionController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-tuition');

        return view('client.student.tuition.index');
    }

    public function showPrint($id)
    {
        $student = Student::withoutGlobalScope('active')
            ->with([
                'grade' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'masterClass' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
            ])
            ->where('status', '!=', 'Trial')
            ->whereId($id)
            ->first();

        $pdf = PDF::loadView('client.student.tuition.print', compact('student'));

        $pdf->setOptions([
            'page-width' => 80,
            'page-height' => 50,
            'margin-top' => 0,
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
        ]);

        return $pdf->inline();
    }

    public function getData()
    {
        checkPermissionTo('view-tuition');

        $students = Student::withoutGlobalScope('active')
            ->with([
                'grade' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'masterClass' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
            ])
            ->where('status', '!=', 'Trial');

        return Datatables::of($students)
            ->addColumn('group', function ($student) {
                return optional($student->masterClass)->code . ' | ' . optional($student->grade)->name;
            })
            ->addColumn('unit', function () {
                return client()->name;
            })
            ->addColumn('account_bank', function () {
                return client()->account_bank . ' | ' . client()->account_number;
            })
            ->addColumn('payment_method', function ($student) {
                return 'Rp ' . thousandSeparator($student->fee + (int)$student->nim);
            })
            ->addColumn('action', function ($student) {
                $print = '<a  class="no-decor text-success btn btn-icon tl-tip btn-sm" data-original-title="Print Kartu SPP" href="../'.$student->id.'/id/print"  target="_blank"><i class="icon wb-print" aria-hidden="true"></i></a>';

                return (userCan('print-student') ? $print : '');
            })
            ->rawColumns(['action', 'unit', 'account_bank', 'group', 'payment_method'])
            ->make(true);
    }
}
