<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::all();
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

        $startDate = request()->start_date ?? Carbon::now()->startOfMonth();
        $endDate = request()->end_date ?? Carbon::now()->endOfMonth();

        $attendanceSummary = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw("
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
        SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days
    ")
            ->first();

        return view('admin.report.details', compact('employee', 'startDate', 'endDate', 'attendanceSummary'));
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
