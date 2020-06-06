<?php

namespace App\Models\Master;

use App\Enum\Staff\StaffStatus;
use App\Models\Master\StaffPosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PositionSalary extends Model
{
    use SoftDeletes;

    protected $table = 'master_position_salaries';

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

    public function position()
    {
        return $this->belongsTo(StaffPosition::class);
    }
}
