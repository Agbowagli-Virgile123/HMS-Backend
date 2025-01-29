<?php

namespace Database\Factories\Patients;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patients\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'firstName' =>fake()->firstName(),
            'lastName' =>fake()->lastName(),
            'dob' =>fake()->date(),
            'gender' => fake()->randomElement(['male','female','other']),
            'phone' =>fake()->phoneNumber(),
            'email' => fake()->email(),
            'address' =>fake()->address(),
            'purpose' =>fake()->randomElement(['Medication','Appointment Booking','Follow-up','Other']),
            'status' =>fake()->randomElement(['pending','registered','discharged','transferred','admitted','deseased']),
            'nhis' =>fake()->randomElement(['NHIS001','NHIS002']),
            'emgRelationship' =>fake()->randomElement(['parent','spouse','sibling','other']),
            'emgPhone' =>fake()->phoneNumber()
            
        ];
    }
}
