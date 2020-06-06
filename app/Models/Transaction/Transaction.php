<?php

namespace App\Models\Transaction;

use Carbon\Carbon;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Transaction\TransactionDetail;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = ['date'];
    protected $casts = [
        'extra' => 'json'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('transactions.client_id', clientId());
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function transactionDetail()
    {
        return $this->hasOne(TransactionDetail::class);
    }

    public function scopeProductTransaction($qry, $from, $to)
    {
        $from = Carbon::parse($from)->format('Y-m-d');
        $to = Carbon::parse($to)->format('Y-m-d');
        return $qry
            ->withoutGlobalScopes()
            ->from('products AS pr')
            ->selectRaw("
                    pr.id,
                    pr.name,
                    (SELECT
                        sum( transaction_details.total )
                    FROM transactions
                        LEFT JOIN transaction_details ON transactions.id = transaction_details.transaction_id
                    WHERE
                        transactions.date BETWEEN ? AND ?
                        AND transaction_details.product_id = pr.id
                        ) AS total_transaction
                ", [$from, $to])
            ->where('pr.client_id', clientId())
            ->groupBy(["pr.id", "pr.name"]);
    }
}
