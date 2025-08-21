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
        Role::create(['name' => 'hr']);
        Role::create(['name' => 'accounts']);
        Role::create(['name' => 'sales']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'designer']);
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'analyst']);

        $internRole =  Role::create(['name' => 'intern']);
        $developerRole =  Role::create(['name' => 'developer']);
        $AdminRole = Role::firstOrCreate(['name' => 'Admin']);

        $admin = User::create([
            'first_name' => 'Admin',
            'email' => '$cuterana73@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $developer = User::create([
            'first_name' => 'Developer',
            'email' => 'developer@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $intern = User::create([
            'first_name' => 'Intern',
            'email' => 'intern@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $intern->assignRole($internRole);
        $developer->assignRole($developerRole);
        $admin->assignRole($AdminRole);
    }
}
