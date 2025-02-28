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
        return Salary::create($data);
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
