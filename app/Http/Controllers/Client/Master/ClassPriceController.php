<?php

namespace App\Http\Controllers\Client\Master;

use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\ClassPrice;
use App\Http\Controllers\Controller;

class ClassPriceController extends Controller
{
    public function getClassPriceData($id)
    {
        $student = Student::findOrFail($id);

        return response()->json([
            'price'    => $student->fee,
        ]);
    }
}
