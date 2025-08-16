<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Salary;

class SalaryService
{

    public function getSalaries()
    {
        return Salary::get();
    }

    public function getEmployees()
    {
        return Employee::get();
    }

    public function storeSalary($data) 
    {
        // dd($data);   
        return Salary::updateOrCreate(
            ['employee_id' => $data['employee_id']],
            [
                'amount' => $data['amount']?? null,
                // 'date' => $data['date'] ?? null ,
                'description' => $data['description']?? null,
            ]
        );
    }

    public function updateSalary($data, $id)
    {
        return Salary::find($id)->update($data);
    }

    public function deleteSalary($id)
    {
        return Salary::find($id)->delete();
    }
}
