<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
        ]);
    }
}

// RoleSeeder: creates Manager, Supervisor, Staff, Non Staff
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'manager',    'display_name' => 'Manager',    'description' => 'Manages teams'],
            ['name' => 'supervisor', 'display_name' => 'Supervisor', 'description' => 'Oversees operations'],
            ['name' => 'staff',      'display_name' => 'Staff',      'description' => 'General staff member'],
            ['name' => 'non_staff',  'display_name' => 'Non Staff',  'description' => 'Contractor or external'],
        ];

        foreach ($roles as $data) {
            Role::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}

// DepartmentSeeder: sample departments
class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Handles recruitment and employee relations'],
            ['name' => 'Finance', 'description' => 'Manages company finances'],
            ['name' => 'IT', 'description' => 'Information Technology support'],
            ['name' => 'Marketing', 'description' => 'Marketing and promotions'],
            ['name' => 'Operations', 'description' => 'Day-to-day operations'],
            ['name' => 'Customer Service', 'description' => 'Customer support and service'],
        ];

        foreach ($departments as $data) {
            Department::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}

// EmployeeSeeder: create one Manager
class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $role       = Role::where('name', 'manager')->first();
        $department = Department::where('name', 'Human Resources')->first();

        Employee::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'first_name'    => 'John',
                'last_name'     => 'Doe',
                'full_name'     => 'John Doe',
                'email'         => 'manager@example.com',
                'phone'         => '081234567890',
                'position'      => 'Manager',
                'department_id' => $department->id,
                'role_id'       => $role->id,
                'date_of_birth' => Carbon::create(1985, 5, 15)->toDateString(),
                'date_joined'   => Carbon::today()->toDateString(),
                'address'       => 'Jl. Sunda No. 22',
                'status'        => 'active',
                'photo_path'    => null,
            ]
        );
    }
}

// UserSeeder: create user for the manager employee
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::where('email', 'manager@example.com')->first();

        if ($employee) {
            User::updateOrCreate(
                ['username' => 'johndoe'],
                [
                    'employee_id' => $employee->id,
                    'username'    => 'johndoe',
                    'email'       => $employee->email,
                    'password'    => Hash::make('password123'),
                ]
            );
        }
    }
}
