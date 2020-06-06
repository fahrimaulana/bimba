<?php

namespace App\Transformers\Master;

use App\Models\Master\Vilage;
use League\Fractal\TransformerAbstract;

class VilageTransformer extends TransformerAbstract
{
    public function transform(Vilage $vilage)
    {
        return [
            'id' => (int) $vilage->id,
            'name' => $vilage->name
        ];
    }
}