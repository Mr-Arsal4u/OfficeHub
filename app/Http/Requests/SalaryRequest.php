<?php

namespace App\Http\Requests;

use App\Models\Salary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $salaryId = $this->route('salary'); // For updates
        
        return [
            'employee_id' => [
                'required',
                'exists:users,id',
                Rule::unique('salaries')->where(function ($query) {
                    return $query->where('month', $this->month)
                                ->where('year', $this->year);
                })->ignore($salaryId),
            ],
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'base_amount' => 'required|numeric|min:0',
            'approved_loans' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|in:pending,paid,cancelled',
            'description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'employee_id.unique' => 'A salary record already exists for this employee for ' . $this->getMonthName($this->month) . ' ' . $this->year . '.',
            'month.required' => 'Please select a month.',
            'month.between' => 'Month must be between 1 and 12.',
            'year.required' => 'Please enter a year.',
            'year.min' => 'Year must be 2020 or later.',
            'base_amount.required' => 'Base salary amount is required.',
            'base_amount.numeric' => 'Base salary amount must be a number.',
            'base_amount.min' => 'Base salary amount cannot be negative.',
            'final_amount.required' => 'Final salary amount is required.',
            'final_amount.numeric' => 'Final salary amount must be a number.',
            'final_amount.min' => 'Final salary amount cannot be negative.',
            'status.in' => 'Status must be pending, paid, or cancelled.',
        ];
    }

    /**
     * Get month name from number
     */
    private function getMonthName($month)
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        return $months[$month] ?? $month;
    }
}
