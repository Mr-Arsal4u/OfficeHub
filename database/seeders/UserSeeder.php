<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $hrRole = Role::firstOrCreate(['name' => 'HR']);
        $accountRole = Role::firstOrCreate(['name' => 'Accounts']);
        $salesRole = Role::firstOrCreate(['name' => 'Sales']);

        // Create 5 users per role
        User::factory(5)->create()->each(function ($user) use ($adminRole, $hrRole, $accountRole, $salesRole) {
            $user->assignRole($adminRole);
            $user->assignRole($hrRole);
            $user->assignRole($accountRole);
            $user->assignRole($salesRole);
        });
    }
}
