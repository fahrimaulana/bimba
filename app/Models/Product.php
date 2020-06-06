<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('products.client_id', clientId());
        });
    }
}