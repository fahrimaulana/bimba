<?php

namespace App\Http\Controllers\Client\ReceiptReport;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReceiptReportController extends Controller
{
    public function index()
    {

        return view('client.receipt-report.index');
    }

    public function viewPdf()
    {
    	$client = client();
        $pdf = PDF::loadView('client.receipt-report.viewpdf', compact('client'));

        $pdf->setOptions([
            'margin-top' => 4,
            'margin-right' => 3,
            'margin-bottom' => 1,
            'margin-left' => 3,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->inline();

    }
}
