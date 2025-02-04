<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Users\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'roleName' => null, // Placeholder (we'll override in the seeder),
        ];
    }
}
