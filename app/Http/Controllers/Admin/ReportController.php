<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::where('id', '!=', Auth::id())
            ->role(['HR', 'Accounts', 'Sales'])
            ->latest()
            ->get();
        return view('admin.report.index', compact('employees'));
    }

    /**
     * Display a listing of the resource.
     */
    public function ai()
    {
        return view('admin.reports.ai_chat');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = User::where('id', $id)->first();

        $startDate = Carbon::parse(request()->start_date ?? Carbon::now()->startOfMonth());

        $endDate = Carbon::parse(request()->end_date ?? Carbon::now()->endOfMonth());


        $attendanceSummary = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw("
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
        SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days")->first();

        $dailySalary = $employee->salary?->amount ?? 0;
        $presentDays = $attendanceSummary->present_days ?? 0;

        $monthlySalary = $dailySalary * $presentDays;

        if (request()->ajax()) {
            return response()->json([
                'present_days' => $attendanceSummary->present_days ?? 0,
                'absent_days' => $attendanceSummary->absent_days ?? 0,
                'leave_days' => $attendanceSummary->leave_days ?? 0,
                'formatted_range' => $startDate->format('M d') . ' - ' . $endDate->format('M d, Y'),
                'monthlySalary' => $monthlySalary,
            ]);
        }

        return view('admin.report.details', compact('employee', 'startDate', 'endDate', 'attendanceSummary', 'monthlySalary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
