<?php

namespace App\Http\Controllers\Accounts;

use App\Enum\ExpenseType;
use Illuminate\Http\Request;
use App\Services\ExpenseService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;

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
            return response()->json(['success' => 'Expense created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(ExpenseRequest $request, $id)
    {
        try {
            $this->expenseService->updateExpense($request->validated(), $id);
            return response()->json(['success' => 'Expense updated successfully.']);
            // return back()->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()]);
            // return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->expenseService->deleteExpense($id);
            return redirect()->back()->with('success', 'Expense deleted successfully.');
            // return response()->json(['success' => 'Expense deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()]);
            return redirect()->back()->with('error', 'An Issue Occured.');
        }
    }
}
