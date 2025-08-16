<?php

namespace App\Services;

use App\Models\User;
use App\Models\Salary;
use App\Models\Loan;
use App\Models\SalaryPayment;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryService
{
    public function getSalaries()
    {
        return Salary::with(['employee', 'payments'])->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
    }

    public function getEmployees()
    {
        return User::where('id', '!=', Auth::id())->get();
    }

    public function storeSalary($data)
    {
        return DB::transaction(function () use ($data) {
            // Check if salary already exists for this employee, month, and year
            $existingSalary = Salary::where([
                'employee_id' => $data['employee_id'],
                'month' => $data['month'],
                'year' => $data['year']
            ])->first();

            if ($existingSalary) {
                throw new Exception('Salary record already exists for this employee for ' . $data['month'] . '/' . $data['year']);
            }

            // Calculate final amount if not provided
            if (!isset($data['final_amount']) || empty($data['final_amount'])) {
                $baseAmount = $data['base_amount'] ?? 0;
                $approvedLoans = $data['approved_loans'] ?? 0;
                $data['final_amount'] = $baseAmount + $approvedLoans;
            }

            $salary = Salary::create([
                'employee_id' => $data['employee_id'],
                'month' => $data['month'],
                'year' => $data['year'],
                'base_amount' => $data['base_amount'],
                'final_amount' => $data['final_amount'],
                'payment_date' => $data['payment_date'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'description' => $data['description'] ?? null,
            ]);

            // If payment date is set, create a payment record
            if ($data['payment_date']) {
                $this->createSalaryPayment($salary, $data);
            }

            return $salary;
        });
    }

    public function updateSalary($data, $id)
    {
        return DB::transaction(function () use ($data, $id) {
            $salary = Salary::findOrFail($id);
            
            // Check if updating month/year would create a duplicate
            if (isset($data['month']) && isset($data['year'])) {
                $existingSalary = Salary::where([
                    'employee_id' => $salary->employee_id,
                    'month' => $data['month'],
                    'year' => $data['year']
                ])->where('id', '!=', $id)->first();

                if ($existingSalary) {
                    throw new Exception('Salary record already exists for this employee for ' . $data['month'] . '/' . $data['year']);
                }
            }

            // Calculate final amount if not provided
            if (!isset($data['final_amount']) || empty($data['final_amount'])) {
                $baseAmount = $data['base_amount'] ?? $salary->base_amount;
                $approvedLoans = $data['approved_loans'] ?? 0;
                $data['final_amount'] = $baseAmount + $approvedLoans;
            }

            $salary->update($data);

            // Update or create payment record if payment date is set
            if (isset($data['payment_date']) && $data['payment_date']) {
                $this->createSalaryPayment($salary, $data);
            }

            return $salary;
        });
    }

    public function deleteSalary($id)
    {
        return Salary::findOrFail($id)->delete();
    }

    public function createSalaryPayment($salary, $data)
    {
        // Create main salary payment
        $payment = SalaryPayment::create([
            'salary_id' => $salary->id,
            'amount' => $salary->final_amount,
            'payment_type' => 'salary',
            'payment_date' => $data['payment_date'],
            'status' => 'completed',
            'notes' => 'Salary payment for ' . $salary->formatted_period
        ]);

        // Check for approved loans for this employee
        $approvedLoans = Loan::where('employee_id', $salary->employee_id)
            ->where('is_approved', 1)
            ->whereDoesntHave('salaryPayments')
            ->get();

        foreach ($approvedLoans as $loan) {
            // Create loan repayment payment
            SalaryPayment::create([
                'salary_id' => $salary->id,
                'loan_id' => $loan->id,
                'amount' => $loan->amount,
                'payment_type' => 'loan_repayment',
                'payment_date' => $data['payment_date'],
                'status' => 'completed',
                'notes' => 'Loan repayment for ' . $loan->type->value . ' loan'
            ]);
        }

        return $payment;
    }

    public function getSalaryWithPayments($id)
    {
        return Salary::with(['employee', 'payments.loan'])->findOrFail($id);
    }

    public function getEmployeeSalaries($employeeId)
    {
        return Salary::with(['payments.loan'])
            ->where('employee_id', $employeeId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    public function getPendingSalaries()
    {
        return Salary::with('employee')
            ->where('status', 'pending')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    public function markSalaryAsPaid($salaryId, $paymentDate = null)
    {
        return DB::transaction(function () use ($salaryId, $paymentDate) {
            $salary = Salary::findOrFail($salaryId);
            $salary->update([
                'status' => 'paid',
                'payment_date' => $paymentDate ?? now()
            ]);

            // Create payment record
            $this->createSalaryPayment($salary, [
                'payment_date' => $paymentDate ?? now()
            ]);

            return $salary;
        });
    }
}
