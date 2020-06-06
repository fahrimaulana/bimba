<?php

namespace App\Http\Controllers\Client\Voucher;

use DB, Datatables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Student\Student;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-voucher-list');

        $students = Student::all();

        return view('client.voucher.index', compact('students'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-voucher');

        $this->validate($request, [
            'code' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'value' => 'required|integer|min:0',
            'student_id' => 'required|integer|' . existsOnCurrentClient('students'),
            'invited_student_id' => 'required|integer|' . existsOnCurrentClient('students'),
            'status' => 'required|in:Penyerahan,Pemakaian'
        ]);

        $voucher = new Voucher;
        $voucher->client_id = clientId();
        $voucher->code = $request->code;
        $voucher->date = Carbon::parse($request->date);
        $voucher->value = $request->value;
        $voucher->student_id = $request->student_id;
        $voucher->invited_student_id = $request->invited_student_id;
        $voucher->status = $request->status;
        $voucher->save();

        return redirect()->route('client.voucher.index')->with('notif_success', 'Voucher baru telah berhasil disimpan!');
    }

    public function edit(Request $request, $id)
    {
        checkPermissionTo('edit-voucher');

        $voucher = Voucher::findOrFail($id);
        $students = Student::all();

        return view('client.voucher.edit', compact('voucher', 'students'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-voucher');

        $this->validate($request, [
            'code' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'value' => 'required|integer|min:0',
            'student_id' => 'required|integer|' . existsOnCurrentClient('students'),
            'invited_student_id' => 'required|integer|' . existsOnCurrentClient('students'),
            'status' => 'required|in:Penyerahan,Pemakaian'
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->code = $request->code;
        $voucher->date = Carbon::parse($request->date);
        $voucher->value = $request->value;
        $voucher->student_id = $request->student_id;
        $voucher->invited_student_id = $request->invited_student_id;
        $voucher->status = $request->status;
        $voucher->save();

        return redirect()->route('client.voucher.index')->with('notif_success', 'Voucher telah berhasil diubah!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-voucher');

        $voucher = Voucher::findOrFail($id);

        $voucher->delete();

        return redirect()->route('client.voucher.index')->with('notif_success', 'Voucher telah berhasil dihapus!');
    }

    public function getData()
    {
        checkPermissionTo('view-voucher-list');

        $vouchers = Voucher::with([
            'student' => function ($qry) {
                $qry->withoutGlobalScopes();
            },
            'invitedStudent' => function ($qry) {
                $qry->withoutGlobalScopes();
            }
        ])->select('vouchers.*')
        ->where(DB::raw('year(vouchers.date)'), year())
        ->where(DB::raw('month(vouchers.date)'), month());

        return Datatables::of($vouchers)
            ->editColumn('date', function ($voucher) {
                return optional($voucher->date)->format('d M Y');
            })
            ->addColumn('student', function ($voucher) {
                $student = optional($voucher->student);

                return
                    "<b>NIM</b>: " . $student->nim . "<br>" .
                    "<b>Nama Murid</b>: " . $student->name . "<br>" .
                    "<b>Orangtua</b>: " . $student->parent_name . "<br>" .
                    "<b>Telp/HP</b>: " . $student->phone;
            })
            ->addColumn('invited_student', function ($voucher) {
                $student = optional($voucher->invitedStudent);

                return
                    "<b>NIM</b>: " . $student->nim . "<br>" .
                    "<b>Nama Murid</b>: " . $student->name . "<br>" .
                    "<b>Orangtua</b>: " . $student->parent_name . "<br>" .
                    "<b>Telp/HP</b>: " . $student->phone;
            })
            ->addColumn('action', function ($voucher) {
                $edit = '<a href="' . route('client.voucher.edit', $voucher->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah voucher"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.voucher.destroy', $voucher->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus voucher"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-voucher') ? $edit : '') . (userCan('delete-voucher') ? $delete : '');
            })
            ->rawColumns(['student', 'invited_student', 'action'])
            ->make(true);
    }

    public function getVoucherData($id)
    {
        $vouchers = Voucher::where('student_id', $id)->get();

        return response()->json(['data' => $vouchers], 200);
    }
}
