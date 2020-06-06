<?php

namespace App\Models\Module;

use App\Models\Staff\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleTransaction extends Model
{
    use SoftDeletes;

    protected $dates = ['date'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('module_transactions.client_id', clientId());
        });

        static::addGlobalScope('period', function (Builder $builder) {
            $builder->whereYear('module_transactions.date', year())
                ->whereMonth('module_transactions.date', month());
        });
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
