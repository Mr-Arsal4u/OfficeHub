<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryPaymentRequest extends FormRequest
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
        return [
            'salary_id' => 'required|exists:salaries,id',
            'loan_id' => 'nullable|exists:loans,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:salary,loan_repayment,advance_payment',
            'payment_date' => 'required|date',
            'status' => 'nullable|in:pending,completed,failed',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'salary_id.required' => 'Salary record is required.',
            'salary_id.exists' => 'Selected salary record does not exist.',
            'loan_id.exists' => 'Selected loan record does not exist.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a number.',
            'amount.min' => 'Payment amount cannot be negative.',
            'payment_type.required' => 'Payment type is required.',
            'payment_type.in' => 'Payment type must be salary, loan_repayment, or advance_payment.',
            'payment_date.required' => 'Payment date is required.',
            'payment_date.date' => 'Payment date must be a valid date.',
            'status.in' => 'Status must be pending, completed, or failed.',
        ];
    }
}
