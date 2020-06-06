<?php

namespace App\Http\Controllers\Client\ProfitSharing;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Preference;
use App\Models\Tuition;

class ProfitSharingController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-profit-sharing');

        return view('client.profit-sharing.index');
    }

    public function viewPdf()
    {
        $year = year();
        $month = month();
        $tuitionQry = Tuition::withoutGlobalScope('period')
            ->selectRaw("
                student_id,
                CASE WHEN MONTH(created_at) = MONTH(NOW()) AND MAX(year) > {$year} OR (MAX(year) = {$year} AND MAX(month) > {$month}) THEN 1 ELSE 0 END as deposit,
                CASE WHEN MONTH(created_at) = MONTH(NOW()) AND SUM(CASE WHEN year < {$year} OR (year = {$year} AND month < {$month}) THEN 1 ELSE 0 END) >= 1 THEN 1 ELSE 0 END as debt,
                CASE WHEN SUM(CASE WHEN year = {$year} AND month = {$month} THEN 1 ELSE 0 END) >= 1 THEN 1 ELSE 0 END as paid,
                CASE WHEN SUM(CASE WHEN year = {$year} AND month = {$month} THEN 1 ELSE 0 END) >= 1 THEN 0 ELSE 1 END as not_paid
            ")
            ->groupBy(['student_id', 'created_at']);

        $departments = Department::selectRaw("
                master_departments.name,
                SUM(CASE WHEN s.status = 'Active' AND s.joined_date < '{$year}-{$month}-01' AND msn.name != 'Aktif Kembali' THEN 1 ELSE 0 END) as active_previous_month,
                SUM(CASE WHEN s.status = 'Active' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' THEN 1 ELSE 0 END) as new_this_month,
                SUM(CASE WHEN s.status = 'Active' AND msn.name = 'Aktif Kembali' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' THEN 1 ELSE 0 END) as active_again,
                SUM(CASE WHEN s.status = 'Out' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' THEN 1 ELSE 0 END) as out_student,
                SUM(CASE WHEN s.status != 'Out' AND s.joined_date < '{$year}-{$month}-01' THEN 1 ELSE 0 END) as active_this_month,
                SUM(CASE WHEN s.status != 'Out' AND mc.scholarship = 'Dhuafa' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' THEN 1 ELSE 0 END) as dhuafa_count,
                SUM(CASE WHEN s.status != 'Out' AND mc.scholarship = 'BNF' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' THEN 1 ELSE 0 END) as bnf_count,
                SUM(CASE WHEN s.status != 'Out' AND msn.name = 'Garansi' AND year(s.joined_date) = '{$year}' AND month(s.joined_date) = '{$month}' THEN 1 ELSE 0 END) as guarantee,
                SUM(CASE WHEN s.status != 'Out' THEN t.deposit ELSE 0 END) as deposit,
                SUM(CASE WHEN s.status != 'Out' THEN t.debt ELSE 0 END) as debt,
                SUM(CASE WHEN s.status != 'Out'  AND mc.scholarship != 'Dhuafa'   AND (msn.name != 'Garansi' OR msn.id IS NULL) THEN 1 ELSE 0 END) as student_to_pay_tuition,
                SUM(CASE WHEN s.status != 'Out'  AND mc.scholarship != 'Dhuafa'   AND (msn.name != 'Garansi' OR msn.id IS NULL) THEN t.paid ELSE 0 END) as paid_tuition,
                SUM(CASE WHEN s.status != 'Out'  AND mc.scholarship != 'Dhuafa'   AND (msn.name != 'Garansi' OR msn.id IS NULL) AND (t.not_paid = 1 OR t.not_paid IS NULL) THEN 1 ELSE 0 END) as not_paid_tuition,
                SUM(CASE WHEN s.status != 'Out'  AND mc.scholarship != 'Dhuafa'   AND (msn.name != 'Garansi' OR msn.id IS NULL) THEN IFNULL(mcp.price, 0) + s.nim ELSE 0 END) as total_tuition
            ")
            ->leftJoin('students as s', function ($qry) {
                $qry->on('s.department_id', 'master_departments.id')
                    ->where('s.status', '!=', 'Trial');
            })
            ->leftJoin('master_classes as mc', 'mc.id', '=', 's.class_id')
            ->leftJoin('master_student_notes as msn', 'msn.id', '=', 's.note_id')
            ->leftJoin('master_class_prices as mcp', function ($qry) {
                $qry->on('mcp.grade_id', 's.grade_id')
                    ->on('mcp.class_id', 'mc.id');
            })
            ->leftJoinSub($tuitionQry, 't', 't.student_id', '=', 's.id')
            ->whereNull('master_departments.deleted_at')
            ->whereNull('s.deleted_at')
            ->where('master_departments.client_id', clientId())
            ->groupBy('master_departments.name')->get();

        $client = client();
        $profitSharingPercentage = Preference::valueOf('profit_sharing_percentage');

        $pdf = PDF::loadView('client.profit-sharing.view-pdf', compact('client', 'departments', 'profitSharingPercentage'));

        $pdf->setOptions([
            'margin-top' => 4,
            'margin-right' => 3,
            'margin-bottom' => 1,
            'margin-left' => 3,
        ]);

        $pdf->setPaper('a5', 'landscape');

        return $pdf->inline();
    }
}
