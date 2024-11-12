<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
// Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('employees/store', [EmployeeController::class, 'store'])->name('employees.store');
// Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
// Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.delete');

Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.delete');
require __DIR__ . '/auth.php';
