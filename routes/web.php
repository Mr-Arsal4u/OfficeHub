<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\Accounts\SalaryController;
use App\Http\Controllers\Accounts\ExpenseController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DashboardController;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    // Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    // Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    // Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.delete');

    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.delete');
    Route::post('attendance/update/ajax', [AttendanceController::class, 'updateAjax'])->name('attendance.update.ajax');
    Route::get('/attendance/filter-by-date', [AttendanceController::class, 'filterByDate'])->name('attendance.filter.by.date');
    // Route::get('/attendance/mark-attendance', [AttendanceController::class, 'markAttendance'])->name('attendance.mark');
    Route::post('/attendance/update-all', [AttendanceController::class, 'updateAll'])->name('attendance.update.all');

    Route::get('salaries', [SalaryController::class, 'index'])->name('salary.index');
    Route::get('salary/create', [SalaryController::class, 'create'])->name('salary.create');
    Route::post('salary', [SalaryController::class, 'store'])->name('salary.store');
    Route::get('salary/{salary}', [SalaryController::class, 'show'])->name('salary.show');
    Route::get('salary/{salary}/edit', [SalaryController::class, 'edit'])->name('salary.edit');
    Route::put('salary/{salary}', [SalaryController::class, 'update'])->name('salary.update');
    Route::delete('salary/{salary}', [SalaryController::class, 'destroy'])->name('salary.delete');
    Route::post('salary/{salary}/mark-paid', [SalaryController::class, 'markAsPaid'])->name('salary.mark-paid');
    Route::get('salary/employee-loans/{employeeId}', [SalaryController::class, 'getEmployeeLoans'])->name('salary.employee-loans');
    Route::get('salary/check-duplicate', [SalaryController::class, 'checkDuplicate'])->name('salary.check-duplicate');

    Route::get('expenses', [ExpenseController::class, 'index'])->name('expense.index');
    Route::post('expense/store', [ExpenseController::class, 'store'])->name('expense.store');
    Route::put('expense/{expense}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('expense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.delete');

    Route::resource('reports', ReportController::class);
    Route::get('/reports/ai', [ReportController::class, 'ai'])->name('reports.ai');

    Route::get('request/payment', [RequestController::class, 'index'])->name('request.payment');
    Route::get('request/payment/create', [RequestController::class, 'create'])->name('request.payment.create');
    Route::post('request/payment', [RequestController::class, 'store'])->name('request.payment.store');
    Route::get('request/payment/{request}', [RequestController::class, 'show'])->name('request.payment.show');
    Route::get('request/payment/{request}/edit', [RequestController::class, 'edit'])->name('request.payment.edit');
    Route::put('request/payment/{request}', [RequestController::class, 'update'])->name('request.payment.update');
    Route::delete('request/payment/{request}', [RequestController::class, 'destroy'])->name('request.payment.delete');
    Route::post('/request/payment/update/status/{id}', [RequestController::class, 'updateStatus'])->name('request.payment.status.update');
});


require __DIR__ . '/auth.php';
