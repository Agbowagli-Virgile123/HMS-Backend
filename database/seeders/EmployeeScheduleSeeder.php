<?php

namespace Database\Seeders;

use App\Models\Users\EmployeeSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       EmployeeSchedule::factory(10)->create();
    }
}
