<?php

namespace App\Http\Controllers\HR;

use Carbon\Carbon;
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
            // $employees = $this->attendanceService->getEmployees();

            $today = Carbon::today()->toDateString();

            $employees = Employee::with(['attendances' => function ($query) use ($today) {
                $query->whereDate('date', $today);
            }])->get();

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
            $data = $this->attendanceService->excludeDates($request->all());
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

    public function updateAjax(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $carbonDate = Carbon::parse($date);

        // Check if Sunday
        if ($carbonDate->isSunday()) {
            return response()->json(['error' => 'Attendance cannot be added on Sunday.'], 422);
        }

        $data = [
            'date' => $date,
        ];

        if ($request->has('status')) {
            $data['status'] = $request->status;
        }
        if ($request->has('check_in_time')) {
            $data['check_in_time'] = $request->check_in_time;
        }
        if ($request->has('check_out_time')) {
            $data['check_out_time'] = $request->check_out_time;
        }

        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $date],
            $data
        );

        return response()->json(['success' => 'Attendance updated']);
    }

    public function filterByDate(Request $request)
    {
        $date = $request->date;
        $employees = Employee::with(['attendances' => function ($query) use ($date) {
            $query->whereDate('date', $date);
        }])->get();

        return view('hr.attendance.attendance_table', compact('employees', 'date'));
    }

    public function updateAll(Request $request)
    {
        $status = $request->input('status');
        $today = Carbon::today()->toDateString();

        $employees = Employee::all();

        foreach ($employees as $employee) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date' => $today
                ],
                [
                    'status' => $status
                ]
            );
        }

        return response()->json(['message' => 'Attendance updated successfully.']);
    }
}
