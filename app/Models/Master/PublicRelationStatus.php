<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicRelationStatus extends Model
{
    use SoftDeletes;

    protected $table = 'master_public_relation_statuses';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function(Builder $builder) {
            $builder->where('master_public_relation_statuses.client_id', clientId());
        });
    }
}