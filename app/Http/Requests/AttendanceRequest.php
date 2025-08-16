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
        $attendanceId = $this->route('attendance') ? $this->route('attendance')->id : null;
        
        return [
            'employee_id' => ['required', 'int'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,leave'],
            // 'check_in_time' => ['required', 'date_format:H:i:s'],
            // 'check_out_time' => ['required', 'date_format:H:i:s'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $employeeId = $this->input('employee_id');
            $date = $this->input('date');
            $attendanceId = $this->route('attendance') ? $this->route('attendance')->id : null;
            
            // Check for duplicate attendance for the same employee on the same date
            $query = \App\Models\Attendance::where('employee_id', $employeeId)
                ->where('date', $date);
            
            // Exclude current attendance record if updating
            if ($attendanceId) {
                $query->where('id', '!=', $attendanceId);
            }
            
            if ($query->exists()) {
                $validator->errors()->add('date', 'Attendance for this employee on this date already exists.');
            }
        });
    }
}
