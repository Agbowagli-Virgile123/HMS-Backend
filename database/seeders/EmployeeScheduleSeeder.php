<?php

namespace Database\Seeders;

use App\Models\Users\EmployeeSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users\User;

class EmployeeScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       //EmployeeSchedule::factory(10)->create();

       $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Loop through all employees
        User::all()->each(function ($user) use ($daysOfWeek) {
            foreach ($daysOfWeek as $day) {
                EmployeeSchedule::factory()->create([
                    'employeeId' => $user->employeeId,
                    'day_of_week' => $day,
                ]);
            }
        });
    }
}
