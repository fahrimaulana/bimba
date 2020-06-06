<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialAllowance extends Model
{
    use SoftDeletes;

    protected $table = 'master_special_allowances';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('master_special_allowances.client_id', clientId());
        });
    }

    public function group()
    {
        return $this->belongsTo(SpecialAllowanceGroup::class);
    }
}
