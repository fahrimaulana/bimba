<?php

namespace App\Http\Controllers\Client\Report\Keuangan;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class VoucherReportController extends Controller
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
        $routeName = 'client.report.keuangan.voucher';

        return view('client.report.keuangan.voucher-list', compact('from', 'to', 'clients', 'routeName'));
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

        $query = Voucher::selectRaw("
                            vouchers.value as voucher,
                            vouchers.date as tanggal,
                            vouchers.`status`,
                            humas.nim AS humas_nim,
                            humas.name AS humas_name,
                            humas.parent_name AS humas_parent,
                            humas.phone AS humas_phone,
                            murid_baru.nim AS murid_nim,
                            murid_baru.name AS murid_name,
                            murid_baru.parent_name AS murid_parent,
                            murid_baru.phone AS murid_phone
                        ")
                        ->leftJoin('students as humas', 'humas.id', '=', 'vouchers.student_id')
                        ->leftJoin('students as murid_baru', 'murid_baru.id', '=', 'vouchers.invited_student_id')
                        ->withoutGlobalScopes(['client'])
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('vouchers.client_id', $request->client_id);
                        })
                        ->whereBetween('vouchers.date', [$fromDate, $toDate])
                        ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:voucher,status',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'VUCHER LIST';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['VOUCHER', 'TANGGAL', 'STATUS', 'NIM HUMAS', 'NAMA HUMAS', 'TELP HUMAS', 'NIM MURID BARU', 'NAMA MURID BARU', 'ORTU MURID BARU', 'TELP MURID BARU'];

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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' VOUCHER LIST' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' VOUCHER LIST ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('VOUCHER LIST ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
