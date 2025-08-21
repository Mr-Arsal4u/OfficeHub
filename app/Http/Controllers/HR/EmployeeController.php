<?php

namespace App\Http\Controllers\HR;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index()
    {
        // Get all employees except the authenticated user
        $employees = User::where('id', '!=', Auth::id())
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Admin');
            })
            ->latest()
            ->get();

        $roles = Role::all();
        return view('hr.employee.index', compact('employees', 'roles'));
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $user = User::create($request->all());
            $user->assignRole($request?->role);
            return response()->json(['success' => 'User created successfully.'], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the user.'], 500);
        }
    }

    public function edit(User $User)
    {
        return view('employees.edit', compact('User'));
    }

    public function update(EmployeeRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            // Prevent editing admin users
            if ($user->hasRole('Admin') && (!Auth::user()->hasRole('Admin'))) {
                return response()->json(['error' => 'Admin users cannot be edited.'], 403);
            }

            $user->update($request->validated());
            if ($request->has('role')) {
                $user->syncRoles($request->role);
            }
            return response()->json(['success' => 'User updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the user.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting admin users
            if ($user->hasRole('Admin')) {
                return response()->json(['error' => 'Admin users cannot be deleted.'], 403);
            }
            $user->delete();
            return response()->json(['success' => 'User deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the user.'], 500);
        }
    }
}
