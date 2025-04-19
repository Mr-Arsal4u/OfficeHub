<?php

namespace App\Models;

use App\Enum\ExpenseType;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['date', 'category', 'amount', 'description'];

    protected $casts = [
        'category' => ExpenseType::class,
    ];
}
