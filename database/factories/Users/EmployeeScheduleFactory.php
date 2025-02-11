<?php

namespace Database\Factories\Users;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Users\User;
use App\Models\Users\EmployeeSchedule;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Users\EmployeeSchedule>
 */
class EmployeeScheduleFactory extends Factory
{
    protected $model = EmployeeSchedule::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'employeeId' => User::inRandomOrder()->value('employeeId') ?? User::factory()->create()->employeeId, // Fetches employeeId instead of id
        //     'day_of_week' => $this->faker->randomElement(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
        //     'start_time' => $this->faker->time('H:i:s'), // Random start time
        //     'end_time' => function (array $attributes) {
        //         return date('H:i:s', strtotime($attributes['start_time'] . ' + 8 hours')); // Adds 8 hours to start time
        //     },
        // ];

        // Define a fixed working time range
        $minStartTime = strtotime('08:00:00');
        $maxEndTime = strtotime('17:00:00');

        // Ensure start time is before end time
        $startTime = date('H:i:s', $this->faker->numberBetween($minStartTime, $maxEndTime - 3600)); // Ensure at least 1-hour gap
        $endTime = date('H:i:s', $this->faker->numberBetween(strtotime($startTime) + 3600, $maxEndTime));

        // Generate appointment times within working hours
        $appointmentStartTime = date('H:i:s', $this->faker->numberBetween(strtotime($startTime), strtotime($endTime) - 1800)); // At least 30 mins before end
        $appointmentEndTime = date('H:i:s', $this->faker->numberBetween(strtotime($appointmentStartTime) + 1800, strtotime($endTime))); // Ensure at least 30 min appointment

        return [
            'employeeId' => User::inRandomOrder()->first()->employeeId,
            'day_of_week' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'appointment_start_time' => $appointmentStartTime,
            'appointment_end_time' => $appointmentEndTime,
        ];
    }
}
