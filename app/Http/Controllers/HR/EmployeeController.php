<?php

namespace App\Http\Controllers\HR;

use App\Models\User;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $roles = Role::all();
        return view('hr.employee.index', compact('employees','roles'));
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        Employee::create($request->all());
        $user = User::create([
            'name' => $request->first_name,
            'email' => $request->email,
            'password' => bcrypt('password'),
        ]);
        $user->assignRole($request->role);
        return response()->json(['success' => 'Employee created successfully.'],201);
        // return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());
        return response()->json(['success' => 'Employee Updated successfully.'],201);
        // return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}

