<?php

namespace Database\Factories\Patients;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patients\Appointment;
use App\Models\Patients\Patient;
use App\Models\Users\User;
use App\Models\Users\Department;

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
        $department = Department::inRandomOrder()->first();
        $departmentId = $department ? $department->id : Department::create([
            'departmentName' => 'General Medicine',
            'purpose' => 'checkup'
        ])->id;



        return [
            'patientId' => Patient::factory(), // Generate a new patient or use existing
            'employeeId' => User::inRandomOrder()->first()->employeeId,
            'department_id' => $departmentId,
            'bookerId' =>User::inRandomOrder()->first()->employeeId,
            'purpose' => $this->faker->randomElement(['Medication', 'Appointment Booking', 'Follow-up', 'Other']),
            'appointmentDate' => $this->faker->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
            'appointmentTime' => $this->faker->time('H:i:s'),
            'status' => $this->faker->randomElement(['pending', 'scheduled', 'completed', 'cancelled', 'checked-in']),
        ];
    }
}
