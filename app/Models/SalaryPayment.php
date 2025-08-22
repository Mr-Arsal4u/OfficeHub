<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id',
        'loan_id',
        'amount',
        'payment_type',
        'payment_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function salary()
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'completed' => 'badge-success',
            'failed' => 'badge-danger'
        ];

        return '<span class="badge ' . ($badges[$this->status] ?? 'badge-secondary') . '">' . ucfirst($this->status) . '</span>';
    }

    public function getPaymentTypeLabelAttribute()
    {
        $labels = [
            'salary' => 'Salary Payment',
            'loan_repayment' => 'Loan Repayment',
            'advance_payment' => 'Advance Payment'
        ];

        return $labels[$this->payment_type] ?? ucfirst(str_replace('_', ' ', $this->payment_type));
    }
}
