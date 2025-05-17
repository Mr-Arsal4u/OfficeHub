<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Salary;
use Illuminate\Http\Request;
use App\Services\SalaryService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;

class SalaryController extends Controller
{

    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function index()
    {
        try {
            $salaries = $this->salaryService->getSalaries();
            $employees = $this->salaryService->getEmployees();
            return view('accounts.salary.index', compact('salaries', 'employees'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            Salary::updateOrCreate(
                ['employee_id' => $request->employee_id],
                [
                    'amount' => $request->amount ?? null,
                    'date' => $request->date ?? null,
                    'description' => $request->description ?? null,
                ]
            );
            return response()->json(['success' => 'Salary created successfully.'], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function update(SalaryRequest $request, $id)
    {
        try {
            $this->salaryService->updateSalary($request->all(), $id);
            return response()->json(['success' => 'Salary updated successfully']);
            // return back()->with('success', 'Salary updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->salaryService->deleteSalary($id);
            return back()->with('success', 'Salary deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
