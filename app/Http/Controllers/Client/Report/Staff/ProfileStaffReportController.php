<?php

namespace App\Http\Controllers\Client\Report\Staff;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use App\Models\Staff\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class ProfileStaffReportController extends Controller
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

        return view('client.report.staff.profile-list', compact('from', 'to', 'clients'));
    }

    public function getQuery(Request $request)
    {
        $fromDate         = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate           = Carbon::parse($request->to_date)->format('Y-m-d');

        $query = Staff::selectRaw("
                            staff.nik,
                            staff.`name` as staff_name,
                            (SELECT master_staff_positions.name FROM master_staff_positions WHERE master_staff_positions.id = staff.position_id LIMIT 1) as jabatan,
                            staff.`status`,
                            (SELECT master_departments.name FROM master_departments WHERE master_departments.id = staff.department_id LIMIT 1) as department,
                            staff.joined_date,
                            '0 thn' AS masa_kerja,
                            staff.birth_date,
                            '31 thn' as usia,
                            staff.phone,
                            staff.email,
                            staff.account_bank,
                            staff.account_number,
                            staff.account_name
                        ")
                        ->withoutGlobalScopes(['client'])
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('staff.client_id', $request->client_id);
                        })
                        ->whereBetween('staff.joined_date', [$fromDate, $toDate])
                        ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {

        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:nik,staff_name,jabatan',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'STAFF PROFILE';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['NIK', 'NAMA', 'JABATAN', 'STATUS', 'DEPARTMENT', 'TGL MASUK', 'MASA KERJA', 'TGL LAHIR', 'USIA', 'NO TELP', 'EMAIL', 'NO REKENING', 'BANK', 'ATAS NAMA'];

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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' STAFF PROFILE' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' STAFF PROFILE ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('STAFF PROFILE ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
