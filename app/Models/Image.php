<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function entity()
    {
        return $this->morphTo();
    }
}
