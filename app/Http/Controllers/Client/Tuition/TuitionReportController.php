<?php

namespace App\Http\Controllers\Client\Tuition;

use App\Models\Tuition;
use Datatables, Schema;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Http\Controllers\Controller;

class TuitionReportController extends Controller
{
    public function index()
    {

        checkPermissionTo('view-tuition-report');

        $tuitionStatistic = $this->getStudentWithTuitionQuery()
            ->selectRaw("
                SUM(CASE WHEN t.is_paid = 1 THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN t.is_paid = 1 THEN mcp.price + nim ELSE 0 END) as paid_total,
                SUM(CASE WHEN t.is_paid = 0 OR t.id IS NULL THEN 1 ELSE 0 END) as unpaid_count,
                SUM(CASE WHEN t.is_paid = 0 OR t.id IS NULL THEN mcp.price + nim ELSE 0 END) as unpaid_total
            ")
            ->whereNotIn('students.status', ['Out', 'Trial'])
            ->where('mc.scholarship', '!=', 'Dhuafa')
            ->whereRaw("(msn.name != 'Garansi' OR msn.id IS NULL)")
            ->first();

        return view('client.tuition.report.index', compact('tuitionStatistic'));
    }

    public function updateNote(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'required|integer|' . existsOnCurrentClient('students'),
            'note' => 'required'
        ]);

        $tuition = Tuition::whereStudentId($request->student_id)->first();
        if (!$tuition) {
            $tuition = new Tuition;
            $tuition->client_id = clientId();
            $tuition->student_id = $request->student_id;
            $tuition->year = year();
            $tuition->month = month();
        }
        $tuition->note = $request->note;
        $tuition->save();

        return redirect()->back()->with('notif_success', 'Keterangan SPP telah berhasil diubah.');
    }

    public function getData()
    {
        checkPermissionTo('view-student-list');

        $students = $this->getStudentWithTuitionQuery()
            ->with([
                'masterClass' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'phase' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'department' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'grade' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'teacher' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'trialTeacher' => function ($qry) {
                    $qry->withoutGlobalScopes();
                },
                'studentNote' => function ($qry) {
                    $qry->withoutGlobalScopes();
                }
            ])
            ->selectRaw("
                students.*,
                mcp.price as fee,
                CASE
                    WHEN
                        t.is_paid = 1 OR
                        students.status IN ('Out', 'Trial') OR
                        mc.scholarship = 'Dhuafa' OR
                        msn.name = 'Garansi'
                    THEN 1 ELSE 0
                END as is_paid,
                t.note as note
            ");
            // dd(getSql($students));
        return Datatables::of($students)
            ->addColumn('info', function ($student) {
                if ($student->status === 'Out') {
                    $status = '<span class="tag tag-danger tl-tip">Tgl Keluar<br>' . optional($student->out_date)->format('d M Y') . '<br><br>Alasan:<br>' . optional($student->outReason)->reason . '</span>';
                } elseif ($student->isNew) {
                    $status = '<span class="tag tag-primary tl-tip">Baru</span>';
                } elseif ($student->status == 'Active') {
                    $status = '<span class="tag tag-success tl-tip">Aktif</span>';
                }

                return
                    "<b>Tahapan</b>: " . optional($student->phase)->name . "<br>" .
                    "<b>Kelas</b>: " . optional($student->department)->name . "<br>" .
                    "<b>Gol</b>: " . optional($student->masterClass)->code . " " .
                    "<b>| KD</b>: " . optional($student->grade)->name . "<br>" .
                    "<b>Guru</b>: " . optional($student->teacher)->name . "<br>" .
                    "<b>Petugas Trial</b>: " . optional($student->trialTeacher)->name . "<br>" .
                    "<b>Status</b>: " . $status . "<br>" .
                    "<b>Note</b>: " . (optional($student->studentNote)->name ?: '-');
            })
            ->editColumn('is_paid', function ($student) {
                return $student->is_paid
                    ? '<i class="icon wb-check text-success"></i>'
                    : '<i class="icon wb-close text-danger"></i>';
            })
            ->editColumn('fee', function ($student) {
                return $student->status === 'Active'
                    ? thousandSeparator($student->fee + (int) $student->nim) : 0;
            })
            ->addColumn('action', function ($student) {
                $editNote = '<a data-student-id="' . $student->id . '" data-note="' . $student->note . '" class="btn btn-sm btn-icon text-primary tl-tip" data-toggle="modal" data-target="#edit-tuition-note-modal" data-toggle="tooltip" data-original-title="Ubah Keterangan SPP"><i class="icon wb-edit" aria-hidden="true"></i></a>';

                return $editNote;
            })
            ->rawColumns(['info', 'is_paid', 'action'])
            ->make(true);
    }

    public function getStudentWithTuitionQuery()
    {
        return Student::withoutGlobalScope('active')
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
            ->where('status', '!=', 'Trial');
    }
}
