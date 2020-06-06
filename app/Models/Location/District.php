<?php

namespace App\Models\Location;

use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class District extends Model
{
    use SoftDeletes;

    protected $table = 'master_districts';
}
