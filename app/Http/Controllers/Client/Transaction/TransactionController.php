<?php

namespace App\Http\Controllers\Client\Transaction;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use DB, Exception, Datatables;
use App\Models\Student\Student;
use App\Models\Student\Tuition;
use App\Models\Master\Department;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use Illuminate\Validation\ValidationException;
use App\Services\Receipt\Process\ReceiptCalculator;

class TransactionController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-transaction-list');

        return view('client.transaction.index');
    }

    public function create()
    {
        checkPermissionTo('create-transaction');

        $students = Student::with(['department'])->get();
        $products = Product::all();
        $vouchers = Voucher::all();
        $departments = Department::all();

        return view('client.transaction.create', compact('students', 'products', 'vouchers', 'departments'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-transaction');

        $this->validate($request, [
            'student_id'     => 'required|integer|exists:students,id,deleted_at,NULL',
            'tuition_month'  => 'required|integer',
            'registration'   => 'nullable',
            'voucher_id'     => 'nullable',
            'event'          => 'nullable',
            'other'          => 'nullable',
            'payment_method' => 'required|in:Cash,Edc,Transfer',
            'receipt_no'     => 'required',
            'joined_date'    => 'required|date_format:d-m-Y',
        ]);


        DB::beginTransaction();
        try {
            $receiptTransactions = collect($request->all());
            $receiptCalculator = (new ReceiptCalculator($receiptTransactions))->calculate();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        }
        DB::commit();

        return redirect()->route('client.transaction.index')->with('notif_success', 'Penerimaan telah berhasil disimpan!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-transaction');

        $transaction = Transaction::findOrFail($id);
        $students = Student::with(['department'])->get();
        $vouchers = Voucher::all();
        $products = Product::all();
        $transactions = Transaction::all();
        $transactionDetail = TransactionDetail::all();
        $departments = Department::all();
        $transactionDetailProducts = TransactionDetail::where('category', 'Product')->where('transaction_id', $id)->get();
        $transactiontuition = Transaction::with([
            'transactionDetail' => function ($qry) {
                $qry->where('transaction_details.category', 'SPP');
            }
        ])->findOrFail($id);
        $transactionDetailEvent = TransactionDetail::where('category', 'Event')->where('transaction_id', $id)->first();
        $transactionDetailOther = TransactionDetail::where('category', 'Others')->where('transaction_id', $id)->first();

        return view('client.transaction.edit', compact('transaction', 'students', 'vouchers', 'products', 'transactionDetailProducts', 'transactiontuition', 'transactionDetailEvent', 'transactionDetailOther', 'transactions', 'transactionDetail', 'departments'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-transaction');
        $this->validate($request, [
            'student_id'     => 'required|integer|exists:students,id,deleted_at,NULL',
            'tuition_month'  => 'required|integer',
            'registration'   => 'nullable',
            'voucher_id'     => 'nullable',
            'event'          => 'nullable',
            'other'          => 'nullable',
            'payment_method' => 'required|in:Cash,Edc,Transfer',
            'receipt_no'     => 'required',
            'joined_date'    => 'required|date_format:d-m-Y',
        ]);


        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            $transactionDetails = TransactionDetail::where('transaction_id', $id)->get();
            $tuitions = Tuition::where('transaction_id', $id)->get();

            foreach ($transactionDetails as $transactionDetail) {
                if ($transactionDetail->voucher_id) {
                    $voucher = Voucher::findOrFail($transactionDetail->voucher_id);
                    $voucher->status = 'Penyerahan';
                    $voucher->save();
                }
                $transactionDetail->delete();
            }

            foreach ($tuitions as $tuition) {
                $tuition->delete();
            }

            $receiptTransactions = collect($request->all());
            $receiptCalculator = (new ReceiptCalculator($receiptTransactions, $filter = 'edit', $transaction))->calculate();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        }

        DB::commit();

        return redirect()->route('client.transaction.index')->with('notif_success', 'Penerimaan telah berhasil disimpan!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-transaction');

        $transaction = Transaction::findOrFail($id);
        $transactionDetails = TransactionDetail::where('transaction_id', $id)->get();
        $tuitions = Tuition::where('transaction_id', $id)->get();

        foreach ($transactionDetails as $transactionDetail) {
            $transactionDetail->delete();
        }

        foreach ($tuitions as $tuition) {
            $tuition->delete();
        }

        $transaction->delete();

        return redirect()->route('client.transaction.index')->with('notif_success', 'Penerimaan telah berhasil dihapus!');
    }

    public function getData()
    {
        checkPermissionTo('view-transaction-list');

        $transactions = Transaction::with(['student' => function ($qry) {
            $qry->withoutGlobalScopes()
                ->where('students.status', '!=', 'Trial');
        }])
        ->where(DB::raw('year(transactions.date)'), year())
        ->where(DB::raw('month(transactions.date)'), month());

        return Datatables::of($transactions)
            ->editColumn('date', function ($transaction) {
                return optional($transaction->date)->format('d M Y');
            })
            ->editColumn('total', function($transaction) {
                return thousandSeparator($transaction->total, 2);
            })
            ->addColumn('action', function ($transaction) {
                $edit = '<a href="' . route('client.transaction.edit', $transaction->id) . '" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Ubah penerimaan"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="' . route('client.transaction.destroy', $transaction->id) . '" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Hapus penerimaan"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-transaction') ? $edit : '') . (userCan('delete-transaction') ? $delete : '');
            })
            ->rawColumns(['action', 'total'])
            ->make(true);
    }
}
