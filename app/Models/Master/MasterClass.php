<?php

namespace App\Models\Master;

use App\Models\Master\ClassPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterClass extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_classes.client_id', clientId());
        });
    }

    public function group()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function classPrices()
    {
        return $this->hasMany(ClassPrice::class, 'class_id');
    }

    public function classPrice()
    {
        return $this->belongsTo(ClassPrice::class, 'class_id');
    }
}
