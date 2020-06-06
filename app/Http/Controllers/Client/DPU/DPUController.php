<?php

namespace App\Http\Controllers\Client\DPU;

use DB;
use App\Charts\DPUChart;
use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;

class DPUController extends Controller
{
    public function index(Request $request)
    {
        checkPermissionTo('view-dpu');

        $departments = Department::all();
        $departmentId = $request->query('department_id', optional($departments->first())->id);

        $initialStudentCount = Student::withoutGlobalScope('active')
            // ->where('joined_date', '<', year() . '-' . month() . '-01')
            ->where(DB::raw('year(joined_date)'), year())
            ->where(DB::raw('month(joined_date)'), month())
            ->whereDepartmentId($departmentId)
            ->count();
            // dd(getSql($initialStudentCount));
        $initialBNFCount = Student::withoutGlobalScope('active')
            ->whereHas('masterClass', function ($qry) {
                $qry->whereScholarship('BNF');
            })
            // ->where('joined_date', '<', year() . '-' . month() . '-01')
            ->where(DB::raw('year(joined_date)'), year())
            ->where(DB::raw('month(joined_date)'), month())
            ->whereDepartmentId($departmentId)
            ->count();
        $initialDhuafaCount = Student::withoutGlobalScope('active')
            ->whereHas('masterClass', function ($qry) {
                $qry->whereScholarship('Dhuafa');
            })
            // ->where('joined_date', '<', year() . '-' . month() . '-01')
            ->where(DB::raw('year(joined_date)'), year())
            ->where(DB::raw('month(joined_date)'), month())
            ->whereDepartmentId($departmentId)
            ->count();

        $stats = Calendar::from('calendars as c')
            ->selectRaw("
                c.d,
                SUM(CASE WHEN DAY(s.joined_date) = c.d  AND `s`.`deleted_at` IS NULL AND  s.status = 'Trial' THEN 1 ELSE 0 END) as trial_count,
                SUM(CASE WHEN DAY(s.joined_date) = c.d  AND `s`.`deleted_at` IS NULL AND  s.status = 'Active' AND DATE(s.joined_date) = c.dt THEN 1 ELSE 0 END) as new_count,
                SUM(CASE WHEN DAY(s.out_date) = c.d  AND `s`.`deleted_at` IS NULL AND  s.status = 'Out' THEN 1 ELSE 0 END) as out_count,
                SUM(CASE WHEN DAY(s.joined_date) = c.d  AND `s`.`deleted_at` IS NULL AND  s.status = 'Active' AND mc.scholarship = 'BNF' THEN 1 ELSE 0 END) as bnf_count,
                SUM(CASE WHEN DAY(s.joined_date) = c.d  AND `s`.`deleted_at` IS NULL AND  s.status = 'Active' AND mc.scholarship = 'Dhuafa' THEN 1 ELSE 0 END) as dhuafa_count
            ")
            ->leftJoin('students as s', function ($qry) use ($departmentId) {
                $qry->where('s.client_id', clientId())
                    ->where(function ($qry) use ($departmentId) {
                        $qry->where(function ($qry) {
                            $qry->whereYear('s.joined_date', year())
                                ->whereMonth('s.joined_date', month());
                        })
                            ->orWhere(function ($qry) {
                                $qry->whereYear('s.out_date', year())
                                    ->whereMonth('s.out_date', month());
                            })
                            ->whereDepartmentId($departmentId);
                    });
            })
            ->leftJoin('master_classes as mc', 'mc.id', '=', 's.class_id')
            ->whereY(year())->whereM(month())
            ->groupBy('c.d')
            ->orderBy('c.d')
            ->get();

        $allStudentCount = 0;
        $allBNFCount = 0;
        $allDhuafaCount = 0;
        foreach ($stats as $stat) {
            $allStudentCount = $allStudentCount + $stat->new_count - $stat->out_count;
            $allBNFCount = $allBNFCount + $stat->bnf_count;
            $allDhuafaCount = $allDhuafaCount + $stat->dhuafa_count;

            $stat->all_count = $allStudentCount;
            $stat->bnf_count = $allBNFCount;
            $stat->dhuafa_count = $allDhuafaCount;
        }

        $dpuChart = (new DPUChart)->labels($stats->pluck('d')->all());
        $dpuChart->dataset('Grafik Pertumbuhan', 'bar', $stats->pluck('all_count'))
            ->backgroundColor('#62a8ea');

        return view('client.dpu.index', compact('departments', 'departmentId', 'stats', 'initialStudentCount', 'initialBNFCount', 'initialDhuafaCount', 'dpuChart'));
    }
}
