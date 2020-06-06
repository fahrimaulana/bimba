<?php

namespace App\Http\Controllers\Client\Student;

use PDF;
use Datatables;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Http\Controllers\Controller;

class StudentMBCController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-mbc');

        return view('client.student.mbc.index');
    }

    public function getData()
    {
        checkPermissionTo('view-mbc');

        $students = Student::withoutGlobalScope('active')
            ->with([
                'grade' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'masterClass' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'department' => function($qry) {
                    $qry->withoutGlobalScopes();
                }
            ])
            ->where('status', '!=', 'Trial');

        return Datatables::of($students)
            ->addColumn('group', function ($student) {
                return optional($student->masterClass)->code . ' | ' . optional($student->grade)->name;
            })
            ->addColumn('kelas',function ($student) {
                return optional($student->department)->code . ' | ' . optional($student->department)->name;
            })
            ->addColumn('payment_method', function ($student) {
                return 'Rp ' . thousandSeparator($student->fee + (int)$student->nim);
            })
            ->addColumn('action', function ($student) {
                $print = '<a class="no-decor text-success btn btn-icon tl-tip btn-sm" data-original-title="Print Kartu MBC Murid" data-url="'.route('client.student.mbc.print1', $student->id).'" data-toggle="modal" data-target="#reprint-receipt-modal"><i class="icon wb-print" aria-hidden="true"></i></a>';

                return (userCan('print-mbc') ? $print : '');
            })
            ->rawColumns(['action','department','group', 'parent_name', 'payment_method'])
            ->make(true);
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
                'department' => function($qry) {
                    $qry->withoutGlobalScopes();
                }
            ])
            ->where('status', '!=', 'Trial')
            ->whereId($id)
            ->first();

        $client = client();
        $kodeBank = substr($client->account_number, 0,5);
        $clientCode = client()->code;

        $client = client();
        $pdf = PDF::loadView('client.student.mbc.print1', compact('client','student', 'clientCode', 'kodeBank'));

        $pdf->setOptions([
            'margin-top' => 4,
            'margin-right' => 3,
            'margin-bottom' => 1,
            'margin-left' => 3,
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->inline();
    }
}