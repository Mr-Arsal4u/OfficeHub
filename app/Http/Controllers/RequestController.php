<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Enum\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoanRequest;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = Loan::with('employee')->orderBy('created_at', 'desc')->get();
        return view('accounts.loan.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('id', '!=', Auth::id())->get();
        $types = LoanType::cases();
        return view('accounts.loan.create', compact('employees', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoanRequest $request)
    {
        try {
            Loan::create($request->validated());
            return redirect()->route('request.payment')->with('success', 'Payment request submitted successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while creating the request.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $loan = Loan::with('employee')->findOrFail($id);
            return view('accounts.loan.show', compact('loan'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $employees = User::where('id', '!=', Auth::id())->get();
            $types = LoanType::cases();
            return view('accounts.loan.edit', compact('loan', 'employees', 'types'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LoanRequest $request, $id)
    {
        try {
            $loan = Loan::findOrFail($id);
            
            // Prevent editing requests for admin users
            if ($loan->employee && $loan->employee->hasRole('admin')) {
                return back()->withInput()->with('error', 'Admin user requests cannot be edited.');
            }
            
            $loan->update($request->validated());
            return redirect()->route('request.payment')->with('success', 'Request updated successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the request.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $loan = Loan::findOrFail($id);
            
            // Prevent deleting requests for admin users
            if ($loan->employee && $loan->employee->hasRole('admin')) {
                return response()->json(['error' => 'Admin user requests cannot be deleted.'], 403);
            }
            
            $loan->delete();
            return response()->json(['success' => 'Request deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the request.'], 500);
        }
    }

    /**
     * Update approval status - Only superadmin can approve
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            // Check if user is admin (superadmin functionality)
            $user = Auth::user();
            if (!$user || !$user->hasRole('admin')) {
                return response()->json(['error' => 'Only admin can approve payment requests.'], 403);
            }

            $loan = Loan::findOrFail($id);
            $newStatus = !$loan->is_approved->value;
            $loan->update(['is_approved' => $newStatus]);
            
            $statusText = $newStatus ? 'approved' : 'rejected';
            return response()->json(['success' => "Payment request {$statusText} successfully."], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while updating the request status.'], 500);
        }
    }
}
