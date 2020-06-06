<?php

namespace App\Models\Master;

use App\Models\Master\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialAllowanceGroup extends Model
{
    use SoftDeletes;

    protected $table = 'master_special_allowance_groups';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('master_special_allowance_groups.client_id', clientId());
        });
    }

    public function allowance()
    {
        return $this->hasOne(SpecialAllowance::class, 'group_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
