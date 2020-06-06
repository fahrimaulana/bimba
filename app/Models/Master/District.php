<?php

namespace App\Models\Master;

use App\Models\Master\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;
    protected $table = 'master_districts';

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
