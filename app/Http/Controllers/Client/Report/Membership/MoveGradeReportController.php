<?php

namespace App\Http\Controllers\Client\Report\Membership;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentClassLog;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class MoveGradeReportController extends Controller
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

        return view('client.report.membership.move-grade-list', compact('from', 'to', 'clients'));
    }

    public function getQuery(Request $request)
    {
        $fromDate         = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate           = Carbon::parse($request->to_date)->format('Y-m-d');

        $query = StudentClassLog::selectRaw("
                        students.nim,
                        students.`name` AS student_name,
                        master_departments.`name` AS department_name,
                        (SELECT master_classes.code FROM master_classes WHERE master_classes.id = student_class_logs.old_class_id limit 1) as old_class_code,
                        (SELECT master_classes.code FROM master_classes WHERE master_classes.id = student_class_logs.new_class_id limit 1) as new_class_code,
                        (SELECT master_grades.name FROM master_grades WHERE master_grades.id = student_class_logs.old_grade_id LIMIT 1) AS old_grade_name,
                        (SELECT master_grades.name FROM master_grades WHERE master_grades.id = student_class_logs.new_grade_id LIMIT 1) AS new_grade_name,
                        student_class_logs.old_price,
                        student_class_logs.new_price,
                        student_class_logs.note
                    ")->leftJoin('students', 'student_class_logs.student_id', '=', 'students.id')
                    ->leftJoin('master_departments', 'students.department_id', '=', 'master_departments.id')
                    ->withoutGlobalScopes(['client'])
                    ->when($request->client_id != 'All', function($qry) use($request) {
                        return $qry->where('student_class_logs.client_id', $request->client_id);
                    })
                    ->whereBetween('student_class_logs.created_at', [$fromDate, $toDate])
                    ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {

        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:nim,student_name,department_name',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'Pindah Gol';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['NIM', 'NAMA MURID', 'KELAS', 'AWAL GOL', 'AWAL KD', 'AWAL SPP', 'PERUBAHAN GOL', 'PERUBAHAN KD', 'PERUBAHAN SPP', 'KETERANGAN'];

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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' Pindah Gol' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' Pindah Gol ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('Pindah Gol ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
