<?php

namespace App\Http\Controllers\Client\Student;

use Datatables;
use PDF, Excel;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Staff\Staff;
use App\Models\Master\Grade;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Models\Master\MasterClass;
use App\Models\Master\StudentNote;
use App\Http\Controllers\Controller;
use App\Models\Student\EducationSertificate;

class StudentCertificateController extends Controller
{
	public function index()
   	{
   		checkPermissionTo('view-certificate');
   		$educationSertificate = EducationSertificate::first();

		return view('client.student.certificate.index', compact('educationSertificate'));
   	}


	public function getData()
    {
        checkPermissionTo('view-certificate');

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
                $print = '<a class="no-decor text-success btn btn-icon tl-tip btn-sm" data-original-title="Print Sertifikat Beasiswa Pendidikan" href="../unit/'.$student->id.'/id/print2" target="_blank"><i class="icon wb-print" aria-hidden="true"></i></a>';

                return (userCan('print-certificate') ? $print : '');
            })
            ->rawColumns(['action','department','group', 'parent_name', 'payment_method'])
            ->make(true);
    }

	public function update(Request $request, $id)
    {
        $this->validate($request, [
        	'amount' => 'required|min:0',
        	'amount_written' => 'required',
            'change_date' => 'required|date_format:d-m-Y',
            'person_in_charge' => 'required'

        ]);

        $educationSertificate = EducationSertificate::FindORFail($id);
        $educationSertificate->amount = $request->amount;
        $educationSertificate->amount_written = $request->amount_written;
        $educationSertificate->change_date = Carbon::parse($request->change_date)->format('Y-m-d');
        $educationSertificate->person_in_charge = $request->person_in_charge;
        $educationSertificate->save();

        return redirect()->route('client.student.certificate.index')->with('notif_success', 'Reward telah berhasil diubah!');
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

        $sertificat = EducationSertificate::first();
        $client = Client::all();

        $pdf = PDF::loadView('client.student.certificate.print', compact('client','student', 'sertificat'));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->inline();
    }
}
