<?php

namespace App\Http\Controllers\Client\Report\Keuangan;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\PettyCash\PettyCash;
use App\Http\Controllers\Controller;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class PettyCashReportController extends Controller
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
        $routeName = 'client.report.keuangan.petty-cash';

        return view('client.report.keuangan.petty-cash-list', compact('from', 'to', 'clients', 'routeName'));
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

        $query = PettyCash::selectRaw("
                        petty_cash_transactions.receipt_no as no_bukti,
                        petty_cash_transactions.date,
                        (SELECT master_petty_cash_categories.name FROM master_petty_cash_categories WHERE master_petty_cash_categories.id =  petty_cash_transactions.category_id LIMIT 1) as kategori,
                        petty_cash_transactions.note as keterangan,
                        petty_cash_transactions.debit,
                        petty_cash_transactions.credit,
                        (SELECT IFNULL(SUM(IFNULL(petty_cash_transactions.debit, 0) - IFNULL(petty_cash_transactions.credit, 0)), 0) FROM petty_cash_transactions LIMIT 1) AS saldo
                        ")
                        ->withoutGlobalScopes(['client', 'period'])
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('petty_cash_transactions.client_id', $request->client_id);
                        })
                        ->whereBetween('petty_cash_transactions.date', [$fromDate, $toDate])
                        ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:no_bukti',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'PETTY CASH LIST';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['NO BUKTI', 'TANGGAL', 'KATEGORI', 'KETERANGAN', 'DEBIT', 'KREDIT', 'SALDO',];

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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' PETTY CASH' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' PETTY CASH ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('PETTY CASH ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
