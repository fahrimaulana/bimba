<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPosition extends Model
{
    use SoftDeletes;

    protected $table = 'master_staff_positions';
    protected $dates = [];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('client', function(Builder $builder) {
    //         $builder->where('master_staff_positions.client_id', clientId());
    //     });
    // }
}