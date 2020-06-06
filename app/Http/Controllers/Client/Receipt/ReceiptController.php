<?php

namespace App\Http\Controllers\Client\Receipt;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Http\Controllers\Controller;

class ReceiptController extends Controller
{
    public function index()
    {
        return view('client.receipt.index');
    }

    public function create()
    {
        $students = Student::all();
        $vouchers = Voucher::all();

        return view('client.receipt.create', compact('students', 'vouchers'));
    }
}
