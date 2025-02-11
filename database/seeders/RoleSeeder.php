<?php

namespace Database\Seeders;

use App\Models\Users\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['doctor', 'receptionist', 'consultant', 'hr', 'cashier', 'admin'];

        // foreach ($roles as $role) {
        //     Role::firstOrCreate(['roleName' => $role]);
        // }

        
        foreach ($roles as $role) {
            Role::updateOrCreate(['roleName' => $role], ['roleName' => $role]);
        }
    }
}
