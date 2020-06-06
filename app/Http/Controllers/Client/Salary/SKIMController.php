<?php

namespace App\Http\Controllers\Client\Salary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\StaffPosition;
use App\Models\Master\PositionSalary;
use App\Enum\Staff\StaffStatus;

class SKIMController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-skim-list');

        $positions = StaffPosition::all();
        $positionSalaries = PositionSalary::whereHas('position')
            ->orderBy('position_id')
            ->orderBy('min_work_length')
            ->orderBy('max_work_length')
            ->orderBy('status')
            ->get();
        $staffStatuses = StaffStatus::all();

        return view('client.salary.skim.index', compact('positionSalaries', 'positions', 'staffStatuses'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-skim');

        $this->validate($request, [
            'position_id' => 'required|integer|' . existsOnCurrentClient('master_staff_positions'),
            'min_work_length' => 'required|integer',
            'max_work_length' => 'required|integer',
            'status' => 'required|in:' . implode(',', StaffStatus::keys()),
            'basic_salary' => 'required|numeric',
            'daily' => 'required|numeric',
            'functional' => 'required|numeric',
            'health' => 'required|numeric',
        ]);

        $positionSalary = new PositionSalary;
        $positionSalary->position_id = $request->position_id;
        $positionSalary->min_work_length = $request->min_work_length;
        $positionSalary->max_work_length = $request->max_work_length;
        $positionSalary->status = $request->status;
        $positionSalary->basic_salary = $request->basic_salary;
        $positionSalary->daily = $request->daily;
        $positionSalary->functional = $request->functional;
        $positionSalary->health = $request->health;
        $positionSalary->save();

        return redirect()->route('client.salary.skim.index')->with('notif_success', 'SKIM gaji baru telah berhasil disimpan!');
    }

    public function updateAll(Request $request)
    {
        checkPermissionTo('edit-skim');

        $this->validate($request, [
            'salaries.*.basic_salary' => 'required|numeric',
            'salaries.*.daily' => 'required|numeric',
            'salaries.*.functional' => 'required|numeric',
            'salaries.*.health' => 'required|numeric'
        ]);

        foreach ($request->salaries as $id => $prices) {
            $positionSalary = PositionSalary::find($id);
            if (!$positionSalary) continue;
            $positionSalary->basic_salary = $prices['basic_salary'];
            $positionSalary->daily = $prices['daily'];
            $positionSalary->functional = $prices['functional'];
            $positionSalary->health = $prices['health'];
            $positionSalary->save();
        }

        return redirect()->route('client.salary.skim.index')->with('notif_success', 'SKIM telah berhasil diperbaharui!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-skim');

        PositionSalary::findOrFail($id)->delete();

        return redirect()->route('client.salary.skim.index')->with('notif_success', 'SKIM telah berhasil dihapus!');
    }
}
