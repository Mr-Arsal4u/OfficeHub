<?php

namespace App\Http\Controllers\Accounts;

use App\Enum\ExpenseType;
use Illuminate\Http\Request;
use App\Services\ExpenseService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index()
    {
        try {
            $expenses = $this->expenseService->getExpenses();
            $expenseCategories = ExpenseType::cases();

            return view('accounts.expense.index', compact('expenses','expenseCategories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(ExpenseRequest $request)
    {
        try {
            $this->expenseService->storeExpense($request->all());
            return response()->json(['success' => 'Expense created successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the expense.'], 500);
        }
    }

    public function update(ExpenseRequest $request, $id)
    {
        try {
            $this->expenseService->updateExpense($request->all(), $id);
            return response()->json(['success' => 'Expense updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the expense.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->expenseService->deleteExpense($id);
            return response()->json(['success' => 'Expense deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the expense.'], 500);
        }
    }
}
