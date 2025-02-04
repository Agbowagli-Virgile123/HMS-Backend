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
            ['departmentName' => 'General Medicine', 'purpose' => 'checkup'],
            ['departmentName' => 'Pediatrics', 'purpose' => 'Child healthcare'],
            ['departmentName' => 'Cardiology', 'purpose' => 'Heart and vascular treatments'],
            ['departmentName' => 'Orthopedics', 'purpose' => 'Bone and joint care'],
            ['departmentName' => 'Dermatology', 'purpose' => 'Skin treatments'],
            ['departmentName' => 'Radiology', 'purpose' => 'Medical imaging and diagnosis'],
            ['departmentName' => 'Surgery', 'purpose' => 'General and specialized surgical procedures'],
            ['departmentName' => 'General Medecine', 'purpose' => 'Consultation '],
            ['departmentName' => 'Psychiatry', 'purpose' => 'Conseling'],
            ['departmentName' => 'Laboratory', 'purpose' => 'Diagnostics'],
            ['departmentName' => 'General Medecine', 'purpose' => 'Treatement'],
            ['departmentName' => 'Immunization Unit', 'purpose' => 'Vaccination'],
            ['departmentName' => 'Wellness & Preventive Medecine', 'purpose' => 'Wellness'],
            ['departmentName' => 'Emergency Unit', 'purpose' => 'Emergency'],


        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['departmentName' => $department['departmentName']], [
                'purpose' => $department['purpose']
            ]);
        }
    }
}

