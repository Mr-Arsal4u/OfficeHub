<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'employee_id' => ['required', 'int'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,leave'],
            // 'check_in_time' => ['required', 'date_format:H:i:s'],
            // 'check_out_time' => ['required', 'date_format:H:i:s'],
        ];
    }
}
