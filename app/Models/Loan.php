<?php

namespace App\Models;

use App\Enum\LoanType;
use App\Enum\RequestIsApproved;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'type',
        'is_approved',
        'refund_percentage',
        'description',
    ];

    protected $casts = [
        'type' => LoanType::class,
        'is_approved' => RequestIsApproved::class,
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            RequestIsApproved::NO->value => 'badge-danger',
            RequestIsApproved::YES->value => 'badge-success'
        ];

        $status = $this->is_approved->value ? 'Approved' : 'Pending';
        return '<span class="badge ' . ($badges[$this->is_approved->value] ?? 'badge-secondary') . '">' . $status . '</span>';
    }
}
