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
        return [
            'employeeId' => User::inRandomOrder()->value('employeeId') ?? User::factory()->create()->employeeId, // Fetches employeeId instead of id
            'day_of_week' => $this->faker->randomElement(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']),
            'start_time' => $this->faker->time('H:i:s'), // Random start time
            'end_time' => function (array $attributes) {
                return date('H:i:s', strtotime($attributes['start_time'] . ' + 8 hours')); // Adds 8 hours to start time
            },
        ];
    }
}
