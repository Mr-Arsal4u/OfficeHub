<?php

namespace App\Enum;

use Illuminate\Support\Str;

enum ExpenseType: string
{
    case FOOD = 'food';
    case TRANSPORT = 'transport';
    case ENTERTAINMENT = 'entertainment';
    case SHOPPING = 'shopping';
    case HEALTH = 'health';
    case OTHER = 'other';
    public function label(): string
    {
        return Str::headline($this->value);
    }
}
