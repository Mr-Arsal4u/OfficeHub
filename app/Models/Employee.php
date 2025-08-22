<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'department',
        'hire_date',
        'user_id',
    ];

    /**
     * Define a relationship with the User model.
     * An employee can be linked to a user account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function sales()
    {
        return $this->hasMany(SalesRecord::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function latestSalary()
    {
        return $this->hasOne(Salary::class, 'employee_id')->latestOfMany('id');
    }
}
