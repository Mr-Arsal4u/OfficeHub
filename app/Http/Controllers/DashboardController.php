<?php

namespace App\Http\Controllers;

use App\Enum\RequestIsApproved;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\SalesRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastYear = Carbon::now()->subMonth()->year;

        // Employee Statistics
        $totalEmployees = User::where('id', '!=', Auth::id())->count();
        $activeEmployees = User::where('id', '!=', Auth::id())->whereHas('attendances', function($query) {
            $query->where('date', '>=', Carbon::now()->subDays(30));
        })->count();

        // Attendance Statistics
        $todayAttendance = Attendance::where('date', Carbon::today())->count();
        $presentToday = Attendance::where('date', Carbon::today())->where('status', 'present')->count();
        $absentToday = Attendance::where('date', Carbon::today())->where('status', 'absent')->count();
        $leaveToday = Attendance::where('date', Carbon::today())->where('status', 'leave')->count();

        // Monthly Attendance Summary
        $monthlyAttendance = Attendance::whereBetween('date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->selectRaw("
            SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
            SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
            SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as leave_days
        ")->first();

        // Salary Statistics
        $totalSalaryThisMonth = Salary::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('final_amount');
        
        $totalSalaryLastMonth = Salary::where('month', $lastMonth)
            ->where('year', $lastYear)
            ->sum('final_amount');

        $salaryGrowth = $totalSalaryLastMonth > 0 ? 
            (($totalSalaryThisMonth - $totalSalaryLastMonth) / $totalSalaryLastMonth) * 100 : 0;

        $pendingSalaries = Salary::where('status', 'pending')->count();
        $paidSalaries = Salary::where('status', 'paid')->count();

        // Expense Statistics
        $totalExpensesThisMonth = Expense::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');
        
        $totalExpensesLastMonth = Expense::whereMonth('date', $lastMonth)
            ->whereYear('date', $lastYear)
            ->sum('amount');

        $expenseGrowth = $totalExpensesLastMonth > 0 ? 
            (($totalExpensesThisMonth - $totalExpensesLastMonth) / $totalExpensesLastMonth) * 100 : 0;

        // Loan Statistics
        $totalLoans = Loan::count();
        $approvedLoans = Loan::where('is_approved', RequestIsApproved::YES)->count();
        $pendingLoans = Loan::where('is_approved', RequestIsApproved::NO)->count();
        $totalLoanAmount = Loan::where('is_approved', RequestIsApproved::YES)->sum('amount');

        // Sales Statistics (if SalesRecord model exists)
        $totalSales = 0;
        $salesGrowth = 0;
        if (class_exists('App\Models\SalesRecord')) {
            $totalSales = SalesRecord::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->sum('amount');
            
            $lastMonthSales = SalesRecord::whereMonth('date', $lastMonth)
                ->whereYear('date', $lastYear)
                ->sum('amount');
            
            $salesGrowth = $lastMonthSales > 0 ? 
                (($totalSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;
        }

        // Monthly Data for Charts
        $monthlyData = $this->getMonthlyData();
        
        // Recent Activities
        $recentAttendances = Attendance::with('employee')
            ->where('date', '>=', Carbon::now()->subDays(7))
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        $recentSalaries = Salary::with('employee')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentExpenses = Expense::orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Department Statistics
        $departmentStats = User::where('id', '!=', Auth::id())
            ->select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->get();

        // Top Performing Employees (by attendance)
        $topEmployees = User::where('id', '!=', Auth::id())
            ->withCount(['attendances' => function($query) {
                $query->where('status', 'present')
                    ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }])
            ->orderBy('attendances_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'todayAttendance',
            'presentToday',
            'absentToday',
            'leaveToday',
            'monthlyAttendance',
            'totalSalaryThisMonth',
            'totalSalaryLastMonth',
            'salaryGrowth',
            'pendingSalaries',
            'paidSalaries',
            'totalExpensesThisMonth',
            'totalExpensesLastMonth',
            'expenseGrowth',
            'totalLoans',
            'approvedLoans',
            'pendingLoans',
            'totalLoanAmount',
            'totalSales',
            'salesGrowth',
            'monthlyData',
            'recentAttendances',
            'recentSalaries',
            'recentExpenses',
            'departmentStats',
            'topEmployees'
        ));
    }

    private function getMonthlyData()
    {
        $months = [];
        $attendanceData = [];
        $salaryData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            // Attendance data
            $attendanceCount = Attendance::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->where('status', 'present')
                ->count();
            $attendanceData[] = $attendanceCount;
            
            // Salary data
            $salaryAmount = Salary::where('month', $date->month)
                ->where('year', $date->year)
                ->sum('final_amount');
            $salaryData[] = $salaryAmount;
            
            // Expense data
            $expenseAmount = Expense::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->sum('amount');
            $expenseData[] = $expenseAmount;
        }

        return [
            'months' => $months,
            'attendance' => $attendanceData,
            'salary' => $salaryData,
            'expense' => $expenseData
        ];
    }
}
