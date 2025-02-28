<?php

namespace App\Services;

use App\Models\Expense;

class ExpenseService
{

    public function getExpenses()
    {
        return Expense::get();
    }

    public function storeExpense($data)
    {
        return Expense::create($data);
    }

    public function updateExpense($data, $id)
    {
        return Expense::where('id', $id)->update($data);
    }

    public function deleteExpense($id)
    {
        return Expense::where('id', $id)->delete();
    }
    
}
