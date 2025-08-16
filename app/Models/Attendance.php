<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'date', 'status', 'check_in_time', 'check_out_time'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
