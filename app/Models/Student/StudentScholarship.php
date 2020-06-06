<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentScholarship extends Model
{
    use SoftDeletes;

    protected $dates = ["start_date", "end_date"];

    public function getStatusAttribute()
    {
        if ($this->end_date->lt(today())) {
            return 'Berakhir';
        } elseif ($this->end_date->diffInMonths(today()->addDay()) == 0) {
            return 'Hampir Berakhir';
        } else {
            return 'Sedang Berjalan';
        }
    }

    public function getColorClassAttribute()
    {
        if ($this->end_date->lt(today())) {
            return 'danger';
        } elseif ($this->end_date->diffInMonths(today()->addDay()) == 0) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}
