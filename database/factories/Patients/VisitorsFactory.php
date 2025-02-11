<?php

namespace Database\Factories\Patients;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patients\Patient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patients\Visitors>
 */
class VisitorsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patientId'=> Patient::inRandomOrder()->first()->patientId,
            'fullName' => fake()->name(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'numOfPeople' => fake()->numberBetween(1, 15) ,
            'relationship' => fake()->randomElement(['spouse','child','other','parent','relative']),
        ];
    }
}
