<?php

namespace App\Transformers\Master;

use App\Models\Master\District;
use League\Fractal\TransformerAbstract;

class DistrictTransformer extends TransformerAbstract
{
    public function transform(District $distirct)
    {
        return [
            'id' => (int) $distirct->id,
            'name' => $distirct->name
        ];
    }
}