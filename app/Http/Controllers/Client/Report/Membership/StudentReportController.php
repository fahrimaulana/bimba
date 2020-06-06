<?php

namespace App\Http\Controllers\Client\Report\Membership;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Http\Controllers\Controller;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class StudentReportController extends Controller
{
    private $type;

    public function __construct()
    {
        $this->type;
    }
    public function showFilter()
    {
        $from = Carbon::now()->startOfMonth()->format('d-m-Y');
        $to = Carbon::now()->endOfMonth()->format('d-m-Y');
        $clients = Client::withoutGlobalScopes()->get();

        return view('client.report.membership.student-list', compact('from', 'to', 'clients'));
    }

    public function getQuery(Request $request)
    {
        $fromDate         = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate           = Carbon::parse($request->to_date)->format('Y-m-d');

        $query = Student::selectRaw("
                            students.nim,
                            students.name,
                            students.birth_place AS tempat_lahir,
                            DATE_FORMAT(students.birth_date ,'%d/%m/%Y') as tgl_lahir,
                            DATE_FORMAT(students.joined_date ,'%d/%m/%Y') as tgl_masuk,
                            CONCAT(TIMESTAMPDIFF (YEAR, students.birth_date, CURDATE()), ' tahun') as usia,
                            CONCAT(TIMESTAMPDIFF (YEAR, students.joined_date, CURDATE()), ' tahun') as lama_bljr,
                            master_student_phases.name as tahap,
                            DATE_FORMAT(students.out_date ,'%d/%m/%Y') as tgl_keluar,
                            master_student_out_reasons.reason AS alasan,
                            master_departments.name as kelas,
                            (SELECT master_classes.code FROM master_classes WHERE master_classes.id = students.class_id LIMIT 1) AS gol,
                            (SELECT master_grades.name FROM master_grades WHERE master_grades.id = students.grade_id LIMIT 1) AS kd,
                            (SELECT master_class_prices.price FROM master_class_prices WHERE master_class_prices.class_id =students.class_id AND master_class_prices.grade_id = students.grade_id LIMIT 1) AS spp,
                            students.status,
                            (SELECT staff.name FROM staff WHERE staff.id = students.trial_teacher_id limit 1) AS petugas_trial,
                            (SELECT staff.name FROM staff WHERE staff.id = students.teacher_id limit 1) AS guru,
                            students.parent_name AS orang_tua,
                            students.phone,
                            (SELECT master_student_notes.name FROM master_student_notes WHERE master_student_notes.id = students.note_id LIMIT 1) AS note,
                            (SELECT master_media_sources.name FROM master_media_sources WHERE master_media_sources.id = students.media_source_id limit 1) as info,
                            students.address as alamat
                        ")
                        ->withoutGlobalScopes(['client', 'active'])
                        ->leftJoin('master_departments', 'master_departments.id', '=', 'students.department_id')
                        ->leftJoin('staff', 'staff.id', '=', 'students.trial_teacher_id')
                        ->leftJoin('master_media_sources', 'master_media_sources.id', '=', 'students.media_source_id')
                        ->leftJoin('master_student_phases', 'master_student_phases.id', '=', 'students.phase_id')
                        ->leftJoin('master_student_out_reasons', 'master_student_out_reasons.id', '=', 'students.out_reason_id')
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('students.client_id', $request->client_id);
                        })
                        ->where('students.status', '!=', 'Trial')
                        ->whereBetween('students.joined_date', [$fromDate, $toDate])
                        ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {

        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:nim,name,tempat_lahir,usia',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'BUKU INDUK';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['NIM', 'NAMA', 'TMPT LAHIR', 'TGL LAHIR', 'TGL MASUK', 'USIA', 'LAMA BLJR', 'TAHAP', 'TGL KELUAR', 'ALASAN', 'KELAS', 'GOL', 'KD', 'SPP', 'STATUS', 'PETUGAS TRIAL', 'GURU', 'ORANGTUA', 'NO TELP/HP', 'NOTE', 'INFO', 'ALAMAT'];

         return compact('title', 'meta', 'query', 'columns');
    }

    public function generateReport(ReportGenerator $reportGenerator, Array $params)
    {
        return $reportGenerator->of($params['title'], $params['meta'], $params['query'], $params['columns'])
            ->setOrientation('landscape');
    }

    public function displayPdf(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $pdfReport = $this->generateReport(new PdfReport, $params)->limit($request->limit);

            return $pdfReport->withoutManipulation()->stream();
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadPdf(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $pdfReport = $this->generateReport(new PdfReport, $params)->limit($request->limit);

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' BUKU INDUK' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadExcel(Request $request)
    {
        try {
            $this->type = 'excel';
            $params = $this->getReportParams($request);
            $excelReport = $this->generateReport(new ExcelReport, $params)->limit($request->limit);

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' BUKU INDUK ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('BUKU INDUK ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
