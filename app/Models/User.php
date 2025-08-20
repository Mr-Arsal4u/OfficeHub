<?php

namespace App\Models;

use App\Enum\LoanType;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'position',
        'department',
        'hire_date',
    ];


    // public function financialTransactions() {
    //     return $this->hasMany(FinancialTransaction::class);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salary()
    {
        return $this->hasOne(Salary::class, 'employee_id');
    }

    public function sales()
    {
        return $this->hasMany(SalesRecord::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function paymentRequests()
    {
        return $this->hasMany(Loan::class, 'employee_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'employee_id', 'id')
            ->where('type', LoanType::LOAN);
    }

    public function advance_payments()
    {
        return $this->hasMany(Loan::class, 'employee_id', 'id')
            ->where('type', LoanType::ADVANCE);
    }
}
