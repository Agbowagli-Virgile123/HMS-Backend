<?php

namespace App\Models\Patients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Users\User;
use App\Models\Users\Department;

class Appointment extends Model
{
    
    use HasFactory;

    protected $guarded = [];

    

     /**
     * Get the patient that owns the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientId', 'patientId');
    }

    /**
     * Get the employee who scheduled the appointment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'employeeId');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
