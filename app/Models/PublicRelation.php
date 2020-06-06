<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Master\PublicRelationStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicRelation extends Model
{
    use SoftDeletes;

    protected $dates = ["registered_date"];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
             $builder->where('public_relations.client_id', clientId());
        });
    }

    public function status()
    {
        return $this->belongsTo(PublicRelationStatus::class);
    }
}
