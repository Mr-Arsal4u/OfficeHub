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
    ];

    protected $casts = [
        'type' => LoanType::class,
        'is_approved' => RequestIsApproved::class,
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
