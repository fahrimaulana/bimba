<?php

namespace App\Http\Controllers\Client\Report\Keuangan;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class PenerimaanReportController extends Controller
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
        $routeName = 'client.report.keuangan.penerimaan';

        return view('client.report.keuangan.penerimaan-list', compact('from', 'to', 'clients', 'routeName'));
    }

    public function generateReport(ReportGenerator $reportGenerator, Array $params)
    {
        return $reportGenerator->of($params['title'], $params['meta'], $params['query'], $params['columns'])
            ->setOrientation('landscape');
    }

    public function getQuery(Request $request)
    {
        $fromDate         = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate           = Carbon::parse($request->to_date)->format('Y-m-d');

        $query = Transaction::selectRaw("
                                transactions.receipt_no as kwitansi,
                                transactions.payment_method as via,
                                transactions.date tanggal,
                                students.nim as nim,
                                students.`name` as nama_murid,
                                    master_departments.NAME AS kelas,
                                    ( SELECT master_classes.CODE FROM master_classes WHERE master_classes.id = students.class_id LIMIT 1 ) AS gol,
                                    ( SELECT master_grades.NAME FROM master_grades WHERE master_grades.id = students.grade_id LIMIT 1 ) AS kd,
                                    students.STATUS,
                                    ( SELECT staff.NAME FROM staff WHERE staff.id = students.teacher_id LIMIT 1 ) AS guru,
                                transactions.total
                        ")
                        ->leftJoin('students', 'students.id', '=', 'transactions.student_id')
                        ->leftJoin('master_departments', 'master_departments.id', '=', 'students.department_id')
                        ->withoutGlobalScopes(['client'])
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('transactions.client_id', $request->client_id);
                        })
                        ->whereBetween('transactions.date', [$fromDate, $toDate])
                        ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:kwitansi,via',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'PENERIMAAN';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['KWITANSI', 'VIA', 'TANGGAL', 'NIM', 'NAMA MURID', 'KELAS', 'GOL', 'KD', 'STATUS', 'GURU', 'TOTAL'];

         return compact('title', 'meta', 'query', 'columns');
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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' PENERIMAAN' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' PENERIMAAN ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('PENERIMAAN ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
