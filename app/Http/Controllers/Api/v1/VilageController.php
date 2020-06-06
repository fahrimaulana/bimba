<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Models\Master\Vilage;
use App\Models\Master\District;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\Transformers\Master\VilageTransformer;

class VilageController extends ApiController
{
    public function getVilage(District $district)
    {
        $vilages = Vilage::where('district_id', $district->id)->orderBy('name')->get();

        return $this->respondTransform($vilages, new VilageTransformer);
    }
}
