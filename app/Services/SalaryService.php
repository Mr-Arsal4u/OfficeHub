<?php

namespace App\Services;

use App\Models\User;
use App\Models\Salary;
use Exception;

class SalaryService
{

    public function getSalaries()
    {
        return Salary::get();
    }

    public function getEmployees()
    {
        return User::doesntHave('salary')->get();
    }

    public function storeSalary($data)
    {
        return Salary::updateOrCreate(
            ['employee_id' => $data['employee_id']],
            [
                'amount' => $data['amount'],
                'date' => $data['date'],
                'bonus' => $data['bonus'],
                'description' => $data['description'],
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
