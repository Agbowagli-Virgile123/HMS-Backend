<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Users\Department;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $role = Role::inRandomOrder()->first();
        $roleId = $role ? $role->id : Role::create(['roleName' => 'receptionist'])->id;

        $department = Department::inRandomOrder()->first();
        $departmentId = $department ? $department->id : Department::create([
            'departmentName' => 'General Medicine',
            'purpose' => 'checkup'
        ])->id;

        return [


            //'employeeId' => 'EMP' . str_pad(User::max('employeeId') + 1, 4, '0', STR_PAD_LEFT),
           'role_id' => $roleId,
            'department_id' => $departmentId,
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone' => fake()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['inactive', 'active', 'terminated']),
            'specialization' =>fake()->sentence(),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
