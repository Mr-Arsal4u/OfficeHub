<?php

namespace App\Http\Controllers\HR;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Services\AttendanceServiceService;
use Illuminate\Support\Facades\Auth;

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

            $employees = User::where('id', '!=', Auth::id())->with(['attendances' => function ($query) use ($today) {
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
        $employees = User::where('id', '!=', Auth::id())->with(['attendances' => function ($query) use ($date) {
            $query->whereDate('date', $date);
        }])->get();

        return view('hr.attendance.attendance_table', compact('employees', 'date'));
    }

    public function updateAll(Request $request)
    {
        $status = $request->input('status');
        $date = $request->input('date', Carbon::today()->toDateString());

        // Validate status
        if (!in_array($status, ['present', 'absent', 'leave'])) {
            return response()->json(['error' => 'Invalid status provided.'], 422);
        }

        // Check if Sunday
        $carbonDate = Carbon::parse($date);
        if ($carbonDate->isSunday()) {
            return response()->json(['error' => 'Attendance cannot be added on Sunday.'], 422);
        }

        // Check if date is in the future
        if ($carbonDate->isFuture()) {
            return response()->json(['error' => 'Cannot set attendance for future dates.'], 422);
        }

        try {
            $employees = User::where('id', '!=', Auth::id())->get();

            if ($employees->isEmpty()) {
                return response()->json(['error' => 'No employees found.'], 404);
            }

            foreach ($employees as $employee) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date' => $date
                    ],
                    [
                        'status' => $status,
                        'check_in_time' => $status === 'present' ? '09:00:00' : null,
                        'check_out_time' => $status === 'present' ? '17:00:00' : null
                    ]
                );
            }

            return response()->json(['success' => 'All employees marked as ' . ucfirst($status) . ' for ' . $date]);
        } catch (\Exception $e) {
            Log::error('Error updating all attendance: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while updating attendance.'], 500);
        }
    }
}
