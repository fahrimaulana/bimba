<?php

namespace App\Http\Controllers;

use App\Location\Province;
use App\Location\City;
use App\Location\District;
use App\Location\Vilage;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    /**
     * Get all province
     *
     * @return string JSON
     */
    public function province()
    {
        return response()->json(Province::orderBy('name', 'ASC')->get());
    }

    /**
     * Get cities based on selected province
     *
     * @return string JSON
     */
    public function city()
    {
        $cities = City::whereProvinceId(request('province'))
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json($cities);
    }

    public function district()
    {
        $districts = District::whereCityId(request('city'))
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json($districts);
    }

    public function vilage()
    {
        $vilages = Vilage::whereDistrictId(request('district'))
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json($vilages);
    }
    public function form()
    {
        return view('location.form')
            ->withTitle('Lokasi');
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'province' => 'required|integer|exists:provinces,id',
            'city' => 'required|integer|exists:cities,id',
            'district' => 'required|integer|exists:districts,id',
            'vilage' => 'required|integer|exists:vilages,id',
        ]);

        // do something here

        return response()->json([
            'status' => true,
            'message' => 'Semua data valid.',
        ]);
    }
}
