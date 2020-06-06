<?php

namespace App\Transformers\Master;

use App\Models\Master\City;
use League\Fractal\TransformerAbstract;

class CityTransformer extends TransformerAbstract
{
    public function transform(City $city)
    {
        return [
            'id' => (int) $city->id,
            'name' => $city->name
        ];
    }
}