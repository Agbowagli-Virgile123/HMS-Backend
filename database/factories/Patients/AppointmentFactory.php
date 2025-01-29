<?php

namespace Database\Factories\Patients;

use Illuminate\Database\Eloquent\Factories\Factory;
us

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patientId' => Patient::factory(), // Generate a new patient or use existing
            'employeeId' => User::factory()->create(['role_id' => 2])->id, // Assuming role_id 2 is for employees
            'department_id' => Department::factory(),
            'bookerId' => User::factory(), // The user who books the appointment
            'purpose' => $this->faker->randomElement(['Medication', 'Appointment Booking', 'Follow-up', 'Other']),
            'appointmentDate' => $this->faker->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
            'appointmentTime' => $this->faker->time('H:i:s'),
            'status' => $this->faker->randomElement(['pending', 'scheduled', 'completed', 'cancelled', 'checked-in']),
        ];
    }
}
