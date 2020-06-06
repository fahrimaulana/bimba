<?php

namespace App\Models\Master;

use App\Models\Master\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;
    protected $table = 'master_provinces';

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
