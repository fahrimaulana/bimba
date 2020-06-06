<?php

namespace App\Services\Receipt\Process;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Student\Student;
use App\Models\Student\Tuition;
use App\Models\Master\Department;
use Illuminate\Support\Collection;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Exceptions\GeneralValidationException;

class ReceiptCalculator
{
    private $receiptTransactions = [];
    private $filter;
    private $transaction;

    public function __construct(Collection $receiptTransactions, $filter = null, $transaction = null)
    {
        $this->filter = $filter;
        $this->receiptTransactions = $receiptTransactions;
        $this->transaction = $transaction;
    }

    public function splitIntoGroups()
    {
        $receiptGroups = collect();
        foreach ($this->receiptTransactions['settings'] as $receiptTransaction) {
            $qty = 1;
            $price = 0;
            $category = '';
            $productId = '';
            $departmentPrice = '';
            if (isset($receiptTransaction['tuition_price']) || isset($receiptTransaction['product'])) {
                if (isset($receiptTransaction['product'])) $product = Product::findOrFail($receiptTransaction['product']);
                if (isset($receiptTransaction['tuition_price'])) {
                    $qty = $this->receiptTransactions['tuition_month'];
                } else {
                    $qty = $receiptTransaction['product_qty'];
                }
                $price = isset($receiptTransaction['tuition_price']) ? $receiptTransaction['tuition_price'] : $product->price;
                $total = $qty * $price;
                $category = isset($receiptTransaction['tuition_price']) ? 'SPP' : 'Product';
                $productId = isset($receiptTransaction['product']) ? $product->id : null;
                $departmentPrice = isset($receiptTransaction['department_id']) ? $department->id : null;
            } else if (isset($receiptTransaction['event']) || isset($receiptTransaction['other'])) {
                $price = isset($receiptTransaction['event']) ? $receiptTransaction['event'] : $receiptTransaction['other'];
                $total = isset($receiptTransaction['event']) ? $receiptTransaction['event'] : $receiptTransaction['other'];
                $category = isset($receiptTransaction['event']) ? 'Event' : 'Others';
            } else if (isset($receiptTransaction['registration']) || isset($receiptTransaction['product'])) {
                $price = isset($receiptTransaction['registration']) ? $receiptTransaction['registration'] : $receiptTransaction['product'];
                $total = isset($receiptTransaction['registration']) ? $receiptTransaction['registration'] : $receiptTransaction['product'];
                $category = isset($receiptTransaction['registration']) ? 'Registration' : 'Product';
            }

            $item = collect();
            $item['qty'] = (int) $qty;
            $item['price'] = (int) $price;
            $item['voucher_id'] = isset($receiptTransaction['voucher_id']) && $receiptTransaction['voucher_id'] ? $this->receiptTransactions['voucher_id'] : null;
            $item['discount'] = isset($receiptTransaction['voucher_id']) && $receiptTransaction['voucher_id'] ? optional($this->getVoucher())->value : null;
            $item['total'] = (int) $qty * $price;
            $item['category'] = $category;
            $item['product_id'] = $productId;
            $item['registration'] = $departmentPrice;

            $receiptGroups->push($item->toArray());
        }

        $this->receiptGroups = $receiptGroups;
        return $this;
    }

    public function calculateEachGroups()
    {
        $transaction                 = ($this->filter == 'edit') ? $this->transaction : new Transaction;
        $transaction->client_id      = client()->id;
        $transaction->date           = Carbon::parse($this->receiptTransactions['joined_date']);
        $transaction->student_id     = $this->receiptTransactions['student_id'];
        $transaction->receipt_no     = $this->receiptTransactions['receipt_no'];
        $transaction->total          = $this->getTotalTransaction();
        $transaction->payment_method = $this->receiptTransactions['payment_method'];
        $transaction->save();

        foreach ($this->receiptGroups as $group) {
            if ($group['category']) {
                if ($group['category'] == 'SPP') $this->storeTuition($group['qty'], $transaction->id);
                $transactionDetail                 = new TransactionDetail;
                $transactionDetail->transaction_id = $transaction->id;
                $transactionDetail->qty            = $group['qty'] ?: 0;
                $transactionDetail->price          = $group['price'] ?: 0;
                $transactionDetail->voucher_id     = $group['voucher_id'] ?: null;
                $transactionDetail->discount       = $group['discount'] ?: null;
                $transactionDetail->total          = $group['discount'] ? (int) ($group['department_id'] + $group['total'] - $group['discount']) : $group['total'];

                $transactionDetail->category       = $group['category'] ?: null;
                $transactionDetail->product_id     = $group['product_id'] ?: null;
                $transactionDetail->save();

                if ($group['voucher_id']) {
                    $voucher = $this->getVoucher();
                    if ($voucher->status == "Pemakaian") {
                        throw new GeneralValidationException('Maaf voucher ini tidak bisa di gunakan lagi');
                    }
                    $voucher->status = 'Pemakaian';
                    $voucher->save();
                }
            }
        }
    }

    public function getVoucher()
    {
        if (isset($this->receiptTransactions['voucher_id']) && $this->receiptTransactions['voucher_id']) $voucher = Voucher::findOrFail($this->receiptTransactions['voucher_id']);

        return $voucher;
    }

    public function getTotalTransaction()
    {
        $totalTransaction = null;
        foreach ($this->receiptGroups as $receipt) {
            $totalTransaction += $receipt['total'] ? ($receipt['total'] - $receipt['discount']) : $receipt['total'];
        }
        return $totalTransaction;
    }

    public function storeTuition($month, $transactionId)
    {
        $tuitionFees = [];
        for ($i = 1; $i <= $month; $i++) {
            $tuitionFees = array_merge($tuitionFees, [$i]);
        }

        $totalmonth = (int) $this->receiptTransactions['month'];
        foreach ($tuitionFees as $tuitionFee) {
            $tuition                 = new Tuition;
            $tuition->client_id      = client()->id;
            $tuition->student_id     = $this->receiptTransactions['student_id'];
            $tuition->transaction_id = $transactionId;
            $tuition->year           = $this->receiptTransactions['year'];
            $tuition->month          = $totalmonth;
            $tuition->is_paid        = '1';
            $tuition->save();
            $totalmonth++;
        }
    }

    public function calculate()
    {
        $this->splitIntoGroups()->calculateEachGroups();

        return $this;
    }
}
