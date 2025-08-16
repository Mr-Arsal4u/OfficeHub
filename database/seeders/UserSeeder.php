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
        // $AdminRole = Role::firstOrCreate(['name' => 'Admin']);
        // $hrRole = Role::firstOrCreate(['name' => 'HR']);
        // $accountRole = Role::firstOrCreate(['name' => 'Accounts']);
        // $salesRole = Role::firstOrCreate(['name' => 'Sales']);

        // // Create 5 users per role
        // User::factory(5)->create()->each(function ($user) use ($AdminRole, $hrRole, $accountRole, $salesRole) {
        //     $user->assignRole($AdminRole);
        //     $user->assignRole($hrRole);
        //     $user->assignRole($accountRole);
        //     $user->assignRole($salesRole);
        // });
        $AdminRole = Role::firstOrCreate(['name' => 'Admin']);
        $user = User::create([
            'first_name' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole($AdminRole);
    }
}
