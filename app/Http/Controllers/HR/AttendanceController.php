<?php

namespace App\Http\Controllers\HR;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Services\AttendanceServiceService;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        try {
            $attendances = $this->attendanceService->getAttendance();
            $employees = $this->attendanceService->getEmployees();
            return view('hr.attendance.index', compact('attendances', 'employees'));
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return back();
        }
    }

    public function store(AttendanceRequest $request)
    {

        try {
            // dd('here');
            $data = $this->attendanceService->excludeDates($request->all());
            $attendance = $this->attendanceService->store($data);
            return response()->json(['success' => 'Attendance created successfully', 201]);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return response()->json(['error' => 'An error occurred while creating attendance', 500]);
        }
    }

    public function update(AttendanceRequest $request, Attendance $attendance)
    {
        try {
            // dd('here');
            $data = $this->attendanceService->excludeDates($request->all());
            // dd($attendance, $data);
            $attendance = $this->attendanceService->update($data, $attendance);
            return response()->json(['success' => 'Attendance updated successfully', 200]);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            return response()->json(['error' => 'An error occurred while Updating attendance', 500]);
        }
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(['success' => 'Attendance deleted successfully', 204]);
    }

    // public function excludeDates($values)
    // {
    //     // dd($values);
    //     unset($values['check_in_time']);
    //     unset($values['check_out_time']);
    //     // dd($values);
    //     return $values;
    // }
}
