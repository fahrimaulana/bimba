<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Master\District;
use Illuminate\Http\Request;
use App\Models\Master\City;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\Master\DistrictTransformer;

class DistrictController extends ApiController
{
    public function getDistrict(City $city)
    {
        $districts = District::where('city_id', $city->id)->orderBy('name')->get();

        return $this->respondTransform($districts, new DistrictTransformer);
    }
}
