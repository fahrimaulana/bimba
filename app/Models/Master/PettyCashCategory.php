<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCashCategory extends Model
{
    use SoftDeletes;

    protected $table = 'master_petty_cash_categories';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('master_petty_cash_categories.client_id', clientId());
        });
    }
}
