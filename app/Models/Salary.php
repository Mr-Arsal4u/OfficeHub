<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 
        'month', 
        'year', 
        'base_amount', 
        'final_amount', 
        'payment_date', 
        'status', 
        'description'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'month' => 'integer',
        'year' => 'integer',
        'base_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function payments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'employee_id', 'employee_id');
    }

    // Helper methods
    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getFormattedPeriodAttribute()
    {
        return $this->month_name . ' ' . $this->year;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'cancelled' => 'badge-danger'
        ];

        return '<span class="badge ' . ($badges[$this->status] ?? 'badge-secondary') . '">' . ucfirst($this->status) . '</span>';
    }
}
