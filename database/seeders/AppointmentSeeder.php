<?php

namespace Database\Seeders;

use App\Models\Patients\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create 15 visitors
        Appointment::factory(15)->create();
    }
}
