<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Enum\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $employees = User::whereHas('paymentRequests')->get();
        $allUsers = User::all();
        $types = LoanType::cases();
        $requests = Loan::with('employee')->get();

        return view('accounts.loan.index', compact('requests', 'allUsers', 'types'));
    }

    public function updateStatus(Request $request, string $id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $loan->update(['is_approved' => !$loan->is_approved?->value]);
            return back()->with('success', 'Request Approved successfully.');
            // return response()->json(['success' => 'Request Approved successfully.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
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
        try {
            Loan::Create(
                [
                    'employee_id' => $request->employee_id ?? null,
                    'type' => $request->type ?? null,
                    'amount' => $request->amount ?? null,
                ]
            );
            return response()->json(['success' => 'Request Submitted successfully.'], 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $loan->update(
                [
                    'employee_id' => $request->employee_id ?? null,
                    'type' => $request->type ?? null,
                    'amount' => $request->amount ?? null,
                ]
            );
            return response()->json(['success' => 'Request Updated successfully.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $loan->delete();
            // return response()->json(['success' => 'Request Deleted successfully.'], 200);
            return redirect()->back()->with('success', 'Request Deleted successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
