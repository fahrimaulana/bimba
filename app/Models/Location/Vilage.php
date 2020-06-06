<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vilage extends Model
{
    use SoftDeletes;

    protected $table = 'master_vilages';
}
