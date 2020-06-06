<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Department extends Model
{
    use SoftDeletes;

    protected $table = 'master_departments';
    protected $dates = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('master_departments.client_id', clientId());
        });
    }

    public function scopeStudentStatistic($qry)
    {
        $year = year();
        $month = month();
        return $qry
            ->withoutGlobalScopes()
            ->from('master_departments as md')
            ->selectRaw("
                md.name,
                SUM(CASE WHEN s.status = 'Trial' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  THEN 1 ELSE 0 END) as trial_count,
                SUM(CASE WHEN s.status = 'Active' AND YEAR(joined_date) = ? AND MONTH(joined_date) = ? THEN 1 ELSE 0 END) as new_count,
                SUM(CASE WHEN s.status = 'Out' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  THEN 1 ELSE 0 END) as out_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND mc.scholarship = 'Dhuafa' THEN 1 ELSE 0 END) as dhuafa_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND mc.scholarship = 'BNF' THEN 1 ELSE 0 END) as bnf_count,

                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(YEAR, birth_date, NOW()) < 3 THEN 1 ELSE 0 END) as age_below_3_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(YEAR, birth_date, NOW()) = 3 THEN 1 ELSE 0 END) as age_3_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(YEAR, birth_date, NOW()) = 4 THEN 1 ELSE 0 END) as age_4_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(YEAR, birth_date, NOW()) = 5 THEN 1 ELSE 0 END) as age_5_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(YEAR, birth_date, NOW()) = 6 THEN 1 ELSE 0 END) as age_6_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(YEAR, birth_date, NOW()) > 6 THEN 1 ELSE 0 END) as age_after_6_count,

                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(MONTH, joined_date, NOW()) < 3 THEN 1 ELSE 0 END) as study_length_below_3_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(MONTH, joined_date, NOW()) BETWEEN 4 AND 6 THEN 1 ELSE 0 END) as study_length_4_to_6_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(MONTH, joined_date, NOW()) BETWEEN 7 AND 12 THEN 1 ELSE 0 END) as study_length_7_to_12_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(MONTH, joined_date, NOW()) BETWEEN 13 AND 18 THEN 1 ELSE 0 END) as study_length_13_to_18_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(MONTH, joined_date, NOW()) BETWEEN 19 AND 24 THEN 1 ELSE 0 END) as study_length_19_to_24_count,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND TIMESTAMPDIFF(MONTH, joined_date, NOW()) > 24 THEN 1 ELSE 0 END) as study_length_after_24_count,

                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND msp.name = 'Persiapan' THEN 1 ELSE 0 END) as preparation_phase,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND msp.name = 'Lanjutan' THEN 1 ELSE 0 END) as advanced_phase,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND msn.name = 'Aktif Kembali' THEN 1 ELSE 0 END) as re_active_student,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND msn.name = 'Cuti' THEN 1 ELSE 0 END) as leave_student,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND msn.name = 'Garansi' THEN 1 ELSE 0 END) as warranty_student,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' AND `s`.`deleted_at` IS NULL  AND msn.name = 'Pindahan' THEN 1 ELSE 0 END) as transfer_student
            ", [year(), month()])
            ->leftJoin("student_logs as s", "s.department_id", "=", "md.id")
            ->leftJoin("master_classes as mc", "mc.id", "=", "s.class_id")
            ->leftJoin("master_student_phases as msp", "msp.id", "=", "s.phase_id")
            ->leftJoin("master_student_notes as msn", "msn.id", "=", "s.note_id")
            ->where('md.client_id', clientId())
            ->whereNull('md.deleted_at')
            ->groupBy(["md.id", "md.name"]);
    }
}
