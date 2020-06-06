<?php

namespace App\Models\Master;

use App\Models\Master\Grade;
use App\Models\Master\MasterClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassPrice extends Model
{
    use SoftDeletes;


    protected $table = 'master_class_prices';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_class_prices.client_id', clientId());
        });
    }

    public function class()
    {
        return $this->belongsTo(MasterClass::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
