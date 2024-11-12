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
        'salary',
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

    /**
     * Define a relationship with the Attendance model.
     * An employee can have multiple attendance records.
     */

    /**
     * Define a relationship with the Sale model.
     * An employee can have multiple sales records.
     */
    public function sales()
    {
        return $this->hasMany(SalesRecord::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

}
