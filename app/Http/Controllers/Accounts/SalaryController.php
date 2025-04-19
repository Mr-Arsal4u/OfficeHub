<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use App\Services\SalaryService;
use Illuminate\Http\Request;

class SalaryController extends Controller
{

    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    public function index()
    {
        try {
            $salaries = $this->salaryService->getSalaries();
            $employees = $this->salaryService->getEmployees();
            return view('accounts.salary.index', compact('salaries','employees'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(SalaryRequest $request)
    {
        try {
            $this->salaryService->storeSalary($request->all());
            return response()->json(['success' => 'Salary created successfully']);
            // return back()->with('success', 'Salary created successfully');
        } catch (\Exception $e) {
            dump($e->getMessage());
            // return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function update(SalaryRequest $request, $id)
    {
        try {
            $this->salaryService->updateSalary($request->all(), $id);
            return response()->json(['success' => 'Salary updated successfully']);
            // return back()->with('success', 'Salary updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->salaryService->deleteSalary($id);
            return back()->with('success', 'Salary deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


}
