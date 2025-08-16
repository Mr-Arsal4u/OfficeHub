<?php

namespace App\Http\Controllers\HR;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::latest()->get();
        $roles = Role::all();
        return view('hr.employee.index', compact('employees', 'roles'));
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        $user =  User::create($request->all());
        $user->assignRole($request?->role);
        return response()->json(['success' => 'User created successfully.'], 201);
        // return redirect()->route('employees.index')->with('success', 'User created successfully.');
    }

    public function edit(User $User)
    {
        return view('employees.edit', compact('User'));
    }

    public function update(EmployeeRequest $request, User $User)
    {
        $User->update($request->all());
        return response()->json(['success' => 'User Updated successfully.'], 201);
        // return redirect()->route('employees.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $User)
    {
        $User->delete();
        return redirect()->route('employees.index')->with('success', 'User deleted successfully.');
    }
}
