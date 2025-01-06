<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
   
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            // Fetch the last patient ID in a safe way
            $lastEmployee = self::latest('id')->first();
            $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;

            // Generate patient_id
            $employee->patient_id = 'Emp-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }
}
