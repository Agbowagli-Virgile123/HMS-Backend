<?php

namespace Database\Seeders;

use App\Models\Users\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['departmentName' => 'General Medicine', 'purpose' => 'checkup','number_of_minute_for_appointment'=> 15],
            ['departmentName' => 'Pediatrics', 'purpose' => 'Child healthcare','number_of_minute_for_appointment'=> 10],
            ['departmentName' => 'Cardiology', 'purpose' => 'Heart and vascular treatments','number_of_minute_for_appointment'=> 13],
            ['departmentName' => 'Orthopedics', 'purpose' => 'Bone and joint care','number_of_minute_for_appointment'=> 20],
            ['departmentName' => 'Dermatology', 'purpose' => 'Skin treatments','number_of_minute_for_appointment'=> 24],
            ['departmentName' => 'Radiology', 'purpose' => 'Medical imaging and diagnosis','number_of_minute_for_appointment'=> 15],
            ['departmentName' => 'Surgery', 'purpose' => 'General and specialized surgical procedures','number_of_minute_for_appointment'=> 19],
            ['departmentName' => 'General Medecine', 'purpose' => 'Consultation','number_of_minute_for_appointment'=> 11],
            ['departmentName' => 'Psychiatry', 'purpose' => 'Conseling','number_of_minute_for_appointment'=> 10],
            ['departmentName' => 'Laboratory', 'purpose' => 'Diagnostics','number_of_minute_for_appointment'=> 16],
            ['departmentName' => 'General Medecine', 'purpose' => 'Treatement','number_of_minute_for_appointment'=> 19],
            ['departmentName' => 'Immunization Unit', 'purpose' => 'Vaccination','number_of_minute_for_appointment'=> 10],
            ['departmentName' => 'Wellness & Preventive Medecine', 'purpose' => 'Wellness','number_of_minute_for_appointment'=> 21],
            ['departmentName' => 'Emergency Unit', 'purpose' => 'Emergency','number_of_minute_for_appointment'=> 18],


        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['departmentName' => $department['departmentName']], 
                ['purpose' => $department['purpose'], 'number_of_minute_for_appointment' => $department['number_of_minute_for_appointment']]
            );
        }

        
    }
}

