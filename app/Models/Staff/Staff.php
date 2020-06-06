<?php

namespace App\Models\Staff;

use App\Enum\Staff\StaffStatus;
use App\Models\Master\Department;
use App\Models\Master\StaffPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $dates = ["joined_date", "birth_date"];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('staff.client_id', clientId());
        });
    }

    public function getIndoStatusAttribute()
    {
        if ($this->status == StaffStatus::Active) {
            $status = 'Aktif';
        } elseif ($this->status == StaffStatus::Intern) {
            $status = 'Magang';
        } elseif ($this->status == StaffStatus::Resign) {
            $status = 'Resign';
        }

        return $status;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(StaffPosition::class);
    }
}
