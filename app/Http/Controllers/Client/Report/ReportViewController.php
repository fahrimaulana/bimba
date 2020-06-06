<?php

namespace App\Http\Controllers\Client\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportViewController extends Controller
{
    public function index()
    {
        return view('client.report-view.index');
    }

    public function showMembershipCategory()
    {
        return view('client.report.category.membership');
    }

    public function showStaffCategory()
    {
        return view('client.report.category.staff');
    }

    public function showHumasCategory()
    {
        return view('client.report.category.humas');
    }

    public function showKeuanganCategory()
    {
        return view('client.report.category.keungan');
    }
}
