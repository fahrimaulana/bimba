<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffDeduction extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('staff_deductions.client_id', clientId());
        });

        static::addGlobalScope('period', function (Builder $builder) {
            $builder->whereYear('staff_deductions.created_at', year())
                ->whereMonth('staff_deductions.created_at', month());
        });
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
