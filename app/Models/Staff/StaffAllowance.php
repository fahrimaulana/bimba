<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\SpecialAllowanceGroup;

class StaffAllowance extends Model
{
    public function group()
    {
        return $this->belongsTo(SpecialAllowanceGroup::class);
    }
}
