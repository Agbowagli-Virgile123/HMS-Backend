<?php

namespace App\Models\Patients;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Patients\Prescription;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $guarded = [];


     /**
     * Get the patient that owns the appointment.
     */
    public function prescriptions()
    {
        return $this->belongsTo(Prescription::class, 'patientId', 'patientId');
    }
}
