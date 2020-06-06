<?php

namespace App\Http\Controllers\General\Session;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function changeLockedPeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer|in:' . implode(',', range(2010, now()->year)),
            'month' => 'required|integer|in:' . implode(',', array_keys(shortMonths()))
        ]);

        if ($validator->fails()) return swalError($validator->errors()->first());

        updateLockedPeriod($request->year, $request->month);

        return redirect()->back();
    }
}
