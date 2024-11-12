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
        return Attendance::create($data);
    }

    public function update($data, $attendance)
    {
        $attendance->update($data);
        return $attendance;
    }
}
