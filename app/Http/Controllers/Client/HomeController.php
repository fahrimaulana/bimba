<?php

namespace App\Http\Controllers\Client;

use DB;
use Carbon\Carbon;
use App\Charts\DPUChart;
use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Models\Student\Student;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Client;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('client.dashboard');
    }

    public function dashboard(Request $request)
    {
        $departments = Department::all();
        $departmentId = $request->query('department_id', optional($departments->first())->id);
        $year = Carbon::now()->year;
        $yearBefore = $year - 1;
        $users = Client::with('roles')->whereClientId(clientId())->oldest()->get();
        $studenByYears = $this->mapStudentByYears($year, $yearBefore, $departmentId);

        $studentStatistic = $this->mapStudentStatistic($departmentId);
        $initialStudentCount = Student::withoutGlobalScope('active')
            ->where('joined_date', '<', year() . '-' . month() . '-01')
            ->whereDepartmentId($departmentId)
            ->count();
        $initialBNFCount = Student::withoutGlobalScope('active')
            ->whereHas('masterClass', function ($qry) {
                $qry->whereScholarship('BNF');
            })
            ->where('joined_date', '<', year() . '-' . month() . '-01')
            ->whereDepartmentId($departmentId)
            ->count();
        $initialDhuafaCount = Student::withoutGlobalScope('active')
            ->whereHas('masterClass', function ($qry) {
                $qry->whereScholarship('Dhuafa');
            })
            ->where('joined_date', '<', year() . '-' . month() . '-01')
            ->whereDepartmentId($departmentId)
            ->count();

        $stats = Calendar::from('calendars as c')
            ->selectRaw("
                c.d,
                SUM(CASE WHEN DAY(s.joined_date) = c.d AND `s`.`deleted_at` IS NULL  AND s.status = 'Trial'  THEN 1 ELSE 0 END) as trial_count,
                SUM(CASE WHEN DAY(s.joined_date) = c.d AND `s`.`deleted_at` IS NULL AND s.status = 'Active' AND DATE(s.joined_date) = c.dt THEN 1 ELSE 0 END) as new_count,
                SUM(CASE WHEN DAY(s.out_date) = c.d AND `s`.`deleted_at` IS NULL AND s.status = 'Out' THEN 1 ELSE 0 END) as out_count,
                SUM(CASE WHEN DAY(s.joined_date) = c.d AND `s`.`deleted_at` IS NULL AND s.status = 'Active' AND mc.scholarship = 'BNF' THEN 1 ELSE 0 END) as bnf_count,
                SUM(CASE WHEN DAY(s.joined_date) = c.d AND `s`.`deleted_at` IS NULL AND s.status = 'Active' AND mc.scholarship = 'Dhuafa' THEN 1 ELSE 0 END) as dhuafa_count
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
                            });
                    });
            })
            ->when($departmentId != 'All', function($qry) use($departmentId) {
                return $qry->where('s.department_id', $departmentId);
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

        $lineStudentByYear = new DPUChart;
        $lineStudentByYear->title('Perbandingan Murid Baru Pertahun');
        $lineStudentByYear->labels($studenByYears->pluck('month')->toArray());
        $lineStudentByYear->dataset('Murid Baru ' . $year, 'bar', $studenByYears->pluck('new_student')->toArray())->backgroundColor('#62a8ea');
        $lineStudentByYear->dataset('Murid Baru ' . $yearBefore, 'bar', $studenByYears->pluck('new_student_before')->toArray())->backgroundColor('#f96868');

        $dpuChart = (new DPUChart)->labels($stats->pluck('d')->all());
        $dpuChart->dataset('Grafik Pertumbuhan', 'bar', $stats->pluck('all_count'))
            ->backgroundColor('#62a8ea');


        $lineStudentStatic = new DPUChart;
        $lineStudentStatic->title('Grafik Murid');
        $lineStudentStatic->labels([Carbon::parse(year() . '-' . month() . '-01')->format('M Y')]);
        $lineStudentStatic->dataset('Murid Aktif', 'bar', $studentStatistic->pluck('active_count')->toArray())->backgroundColor('#e8ae08');
        $lineStudentStatic->dataset('Murid Baru', 'bar', $studentStatistic->pluck('new_count')->toArray())->backgroundColor('#62a8ea');
        $lineStudentStatic->dataset('Murid Keluar', 'bar', $studentStatistic->pluck('out_count')->toArray())->backgroundColor('#f10303');



        return view('client.dashboard', compact('users', 'lineStudentByYear', 'dpuChart', 'lineStudentStatic', 'departments', 'departmentId'));
    }

    public function mapStudentByYears($year, $yearBefore, $departmentId)
    {
        $clientId = clientId();
        return Calendar::selectRaw("
                    SUBSTRING(calendars.monthName, 1, 3) AS month,
                    IFNULL(SUM(CASE WHEN students.status = 'Active' AND `students`.`deleted_at` IS NULL  AND YEAR(students.joined_date) = ? THEN 1 ELSE 0 END), 0) as new_student_before,
                    IFNULL(SUM(CASE WHEN students.status = 'Active' AND `students`.`deleted_at` IS NULL AND YEAR(students.joined_date) = ? THEN 1 ELSE 0 END), 0) as new_student
                ", [$yearBefore, $year])
                ->from(DB::raw("(SELECT distinct(m), monthName from calendars) as calendars"))
                ->leftJoin("students", function($qry) use($year, $yearBefore, $clientId, $departmentId) {
                    $qry->on('calendars.m', '=', DB::raw('MONTH(students.joined_date)'))
                        ->on(function($qry) use($yearBefore, $year, $departmentId) {
                            $qry->on(DB::raw("YEAR(students.joined_date)"), '=', DB::raw($yearBefore))
                                ->orOn(DB::raw("YEAR(students.joined_date)"), '=', DB::raw($year))
                                ->when($departmentId != 'All', function($qry) use($departmentId) {
                                    return $qry->where('students.department_id', $departmentId);
                                });

                        })
                        ->on('client_id', '=', DB::raw($clientId));
                })
                ->groupBy(DB::raw('calendars.m'), DB::raw('calendars.monthName'))->get();
    }

    public function mapStudentStatistic($departmentId)
    {
        $year = year();
        $month = month();

        return Student::selectRaw("
            SUM(CASE WHEN students.status = 'Active' AND year(students.joined_date) = '{$year}'  THEN 1 ELSE 0 END) as active_count,
            SUM(CASE WHEN students.status = 'Active' AND year(students.joined_date) = '{$year}' AND month(students.joined_date) = '{$month}' THEN 1 ELSE 0 END) as new_count,
            SUM(CASE WHEN students.status = 'Out' AND year(students.joined_date) = '{$year}' AND month(students.joined_date) = '{$month}' THEN 1 ELSE 0 END) as out_count
                    ")->withoutGlobalScopes(['active'])
            ->when($departmentId != 'All', function($qry) use($departmentId) {
                return $qry->where('students.department_id', $departmentId);
            })->get();
    }
}
