<?php

namespace App\Models\PettyCash;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\PettyCashCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCash extends Model
{
    use SoftDeletes;

    protected $table = 'petty_cash_transactions';
    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('petty_cash_transactions.client_id', clientId());
        });

        static::addGlobalScope('period', function (Builder $builder) {
            $builder->whereYear('petty_cash_transactions.date', year())
                ->whereMonth('petty_cash_transactions.date', month());
        });
    }

    public function category()
    {
        return $this->belongsTo(PettyCashCategory::class);
    }
}
