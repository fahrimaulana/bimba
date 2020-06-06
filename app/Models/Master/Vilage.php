<?php

namespace App\Models\Master;

use App\Models\Master\Distict;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vilage extends Model
{
    use SoftDeletes;

    protected $revisionCreationsEnabled = true;
    protected $table = 'master_vilages';

    public function distirct()
    {
        return $this->belongsTo(Distict::class);
    }
}
