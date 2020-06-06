<?php

namespace App\Http\Controllers\Client\Report\Membership;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student\TrialStudent;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class TrialStudentReportController extends Controller
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

        return view('client.report.membership.trial-student-list', compact('from', 'to', 'clients'));
    }

    public function getQuery(Request $request)
    {
        $fromDate         = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate           = Carbon::parse($request->to_date)->format('Y-m-d');

        $query = TrialStudent::selectRaw("
                            DATE_FORMAT(students.joined_date ,'%d/%m/%Y') as tgl_mulai,
                            master_departments.`name` as kelas,
                            students.`name` as nama,
                            DATE_FORMAT(students.birth_date ,'%d/%m/%Y') as tgl_lahir,
                            CONCAT(TIMESTAMPDIFF (YEAR, students.birth_date, CURDATE()), ' tahun') as usia,
                            staff.name AS guru_trial,
                            master_media_sources.name as info,
                            students.parent_name as orang_tua,
                            students.phone,
                            students.address
                        ")
                        ->withoutGlobalScopes(['client'])
                        ->leftJoin('master_departments', 'master_departments.id', '=', 'students.department_id')
                        ->leftJoin('staff', 'staff.id', '=', 'students.trial_teacher_id')
                        ->leftJoin('master_media_sources', 'master_media_sources.id', '=', 'students.media_source_id')
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('students.client_id', $request->client_id);
                        })
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
            'sort_by'   => 'required|in:tgl_mulai,kelas,nama,usia',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'DATA MURID TRIAL BARU';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['TGL MULAI', 'KELAS', 'NAMA', 'TGL LAHIR', 'USIA', 'GURU TRIAL', 'INFO', 'ORANGTUA', 'NO TELP/HP', 'ALAMAT'];

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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' DATA MURID TRIAL BARU' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' DATA MURID TRIAL BARU ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('DATA MURID TRIAL BARU ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
