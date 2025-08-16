<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        // dd($this->all());
        return [
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric',
            // 'date' => 'required|string|max:255',
            'bonus' => 'nullable|numeric',
            'description' => 'nullable',
        ];
    }
}
