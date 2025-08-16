<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;

class AttendanceService
{

    public function getAttendance()
    {
        return Attendance::get();
    }

    public function getEmployees()
    {
        return Employee::get();
    }

    public function excludeDates($request)
    {
        $data = $request;
        // dd($data);
        if ($data['status'] == 'absent' || $data['status'] == 'leave') {
            // unset($data['check_in_time']);
            // unset($data['check_out_time']);

            $data['check_in_time'] = null;
            $data['check_out_time'] = null;
        }
        // dd($data);
        return $data;
    }

    public function store($data)
    {
        // Check for existing attendance for the same employee on the same date
        $existingAttendance = Attendance::where('employee_id', $data['employee_id'])
            ->where('date', $data['date'])
            ->first();
            
        if ($existingAttendance) {
            throw new \Exception('Attendance for this employee on this date already exists.');
        }
        
        return Attendance::create($data);
    }

    public function update($data, $attendance)
    {
        $attendance->update($data);
        return $attendance;
    }
}
