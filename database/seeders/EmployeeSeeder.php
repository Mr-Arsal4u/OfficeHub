<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        Employee::create([
            'first_name' => 'Ahmad',
            'last_name' => 'Sikandar',
            'email' => 'ahmad.test@example.com',
            'phone' => '1234567890',
            'position' => 'Admin',
            'department' => 'Management',
            'hire_date' => now(),
            'user_id' => $user->id, // link to the user
        ]);
    }
}
