<?php

namespace App\Http\Controllers\Client\Report\Humas;

use Carbon\Carbon;
use DB, Exception;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\PublicRelation;
use App\Http\Controllers\Controller;
use Jimmyjs\ReportGenerator\ReportGenerator;
use Jimmyjs\ReportGenerator\ReportMedia\CSVReport;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;
use Jimmyjs\ReportGenerator\ReportMedia\ExcelReport;

class HumasReportController extends Controller
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

        return view('client.report.humas.humas-list', compact('from', 'to', 'clients'));
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

        $query = PublicRelation::selectRaw("
                            public_relations.registered_date,
                            public_relations.nih,
                            public_relations.`name`,
                            (SELECT master_public_relation_statuses.name FROM master_public_relation_statuses WHERE master_public_relation_statuses.id = public_relations.status_id LIMIT 1) as status,
                            public_relations.phone,
                            public_relations.job
                        ")
                        ->withoutGlobalScopes(['client'])
                        ->when($request->client_id != 'All', function($qry) use($request) {
                            return $qry->where('public_relations.client_id', $request->client_id);
                        })
                        ->whereBetween('public_relations.registered_date', [$fromDate, $toDate])
                        ->orderBy($request->sort_by, $request->order_by);

        return $query;
    }

    public function getReportParams(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required|date_format:d-m-Y',
            'to_date'   => 'required|date_format:d-m-Y',
            'client_id' => 'nullable',
            'sort_by'   => 'required|in:nih,humas_nama,status',
            'order_by'  => 'required|in:asc,desc',
            'limit'     => 'nullable|integer',
        ]);

        $title    = 'HUMAS';
        $fromDate = Carbon::parse($request->from_date)->format('d/m/Y');
        $toDate = Carbon::parse($request->to_date)->format('d/m/Y');
        $client = ($request->client_id == 'All') ? 'Semua Client' : Client::findOrFail($request->client_id)->name;

        $meta = [
            'Tanggal' => $fromDate . ' untuk '.$toDate,
            'Order By'  => space_case($request->sort_by) . ', ' . space_case($request->order_by),
            'Client'      => $client
        ];

        $query = $this->getQuery($request);

        $columns = ['TGL REG', 'NIH', 'NAMA', 'STATUS', 'NO TELP/HP', 'PEKERJAAN', 'ALAMAT'];

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

            return $pdfReport->withoutManipulation()->download(space_case($request->status) . ' HUMAS LIST' . date('d M Y'));
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

            return $excelReport->withoutManipulation()->simpleDownload(space_case($request->status) . ' HUMAS LIST ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }

    public function downloadCsv(Request $request)
    {
        try {
            $params = $this->getReportParams($request);
            $csvReport = $this->generateReport(new CSVReport, $params)->limit($request->limit);

            return $csvReport->showMeta()->withoutManipulation()->download('HUMAS LIST ' . date('d M Y'));
        } catch (Exception $e) {
            return validationError($e->getMessage());
        }
    }
}
