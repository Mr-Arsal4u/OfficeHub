<?php

namespace App\Enum;

use Illuminate\Support\Str;

enum LoanType: string
{
    case ADVANCE = 'advance';
    case LOAN = 'loan';

    public function label()
    {
        return Str::headline($this->value);
    }
}
