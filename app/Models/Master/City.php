<?php

namespace App\Models\Master;

use App\Models\Master\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;
    protected $table = 'master_cities';

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
