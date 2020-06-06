<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Master\City;
use Illuminate\Http\Request;
use App\Models\Master\Province;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\Master\CityTransformer;

class CityController extends ApiController
{
    public function getCity(Province $province)
    {
        $cities = City::where('province_id', $province->id)->orderBy('name')->get();

        return $this->respondTransform($cities, new CityTransformer);
    }
}
