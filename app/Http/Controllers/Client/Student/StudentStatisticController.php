<?php

namespace App\Http\Controllers\Client\Student;

use App\Models\Master\Department;
use App\Http\Controllers\Controller;

class StudentStatisticController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-student-statistic');

        $statistics = Department::studentStatistic()->get();

        return view('client.student.statistic.index', compact('statistics'));
    }
}
