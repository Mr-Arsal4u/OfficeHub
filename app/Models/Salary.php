<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = ['employee_id', 'date', 'amount', 'description'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
