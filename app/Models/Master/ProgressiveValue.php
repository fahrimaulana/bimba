<?php

namespace App\Models\Master;

use App\Models\Master\StaffPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressiveValue extends Model
{
    protected $table = 'master_progressive_values';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_progressive_values.client_id', clientId());
        });
    }

    public function position()
    {
        return $this->belongsTo(StaffPosition::class);
    }
}