<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Loan;
use App\Models\User;
use App\Models\Salary;
use Illuminate\Http\Request;
use App\Models\SalaryPayment;
use App\Enum\RequestIsApproved;
use App\Services\SalaryService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function index()
    {
        $salaries = $this->salaryService->getSalaries();
        return view('accounts.salary.index', compact('salaries'));
    }

    public function create()
    {
        $employees = User::where('id', '!=', Auth::id())->get();
        return view('accounts.salary.create', compact('employees'));
    }

    public function store(SalaryRequest $request)
    {
        try {
            $salary = $this->salaryService->storeSalary($request->validated());
            return redirect()->route('salary.index')->with('success', 'Salary created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $salary = $this->salaryService->getSalaryWithPayments($id);
            return view('accounts.salary.show', compact('salary'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $salary = $this->salaryService->getSalaryWithPayments($id);
            $employees = User::where('id', '!=', Auth::id())->get();
            return view('accounts.salary.edit', compact('salary', 'employees'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(SalaryRequest $request, $id)
    {
        try {
            $updatedSalary = $this->salaryService->updateSalary($request->validated(), $id);
            return redirect()->route('salary.index')->with('success', 'Salary updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->salaryService->deleteSalary($id);
            return response()->json(['success' => 'Salary deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function markAsPaid(Request $request, $id)
    {
        try {
            $paymentDate = $request->input('payment_date', now());
            $salary = $this->salaryService->markSalaryAsPaid($id, $paymentDate);
            return response()->json(['success' => 'Salary marked as paid successfully', 'salary' => $salary], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEmployeeLoans($employeeId)
    {
        try {
            $loans = Loan::where('employee_id', $employeeId)
                ->where('is_approved', RequestIsApproved::YES)
                ->whereDoesntHave('salaryPayments')
                ->get();

            $pendingRepayments = SalaryPayment::whereHas('salary', function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            })
                ->where('status', 'pending')
                ->with('loan')
                ->get();

            return response()->json([
                'loans' => $loans,
                'pendingRepayments' => $pendingRepayments
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkDuplicate(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $month = $request->input('month');
            $year = $request->input('year');
            $salaryId = $request->input('salary_id'); // For updates

            $query = Salary::where([
                'employee_id' => $employeeId,
                'month' => $month,
                'year' => $year
            ]);

            // Exclude current record if updating
            if ($salaryId) {
                $query->where('id', '!=', $salaryId);
            }

            $exists = $query->exists();

            return response()->json(['exists' => $exists]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
