<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Users\Role;
use App\Models\Users\Department;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //UserSeeder::factory(10)->create();

        $role = Role::inRandomOrder()->first();
        $roleId = $role ? $role->id : Role::create(['roleName' => 'receptionist'])->id;

        $department = Department::inRandomOrder()->first();
        $departmentId = $department ? $department->id : Department::create([
            'departmentName' => 'General Medicine',
            'purpose' => 'checkup'
        ])->id;

        User::factory()->create([
            'role_id' =>   $roleId ,
            'department_id' => $departmentId,
            'firstName' => 'Alpha',
            'lastName' => 'Beta',
            'email' => 'alpha@gmail.com',
            'email_verified_at' => now(),
            'phone' => '04433992288',
            'password' => 'password',
            'status' => 'active',
            'specialization' =>'',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->call([
            DepartmentSeeder::class,
            RoleSeeder::class,
            PatientSeeder::class,
            UserSeeder::class,
            EmployeeScheduleSeeder::class,
            VisitorSeeder::class,
        ]);
    }
}
