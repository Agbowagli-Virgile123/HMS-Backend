<?php

namespace App\Models\Patients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'patientId'; // Set primary key
    public $incrementing = false; // patientId is not auto-incrementing
    protected $keyType = 'string'; // patientId is a string

    protected $guarded = []; // Allow mass assignment for all attributes

    /**
     * Boot method for the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            // Generate a trimmed UUID-based patientId if not already set
            if (empty($patient->patientId)) {
                $uuid = Str::uuid()->toString(); // Generate a UUID
                $patient->patientId = 'P-' . substr($uuid, 0, 4); // Use the first 4 characters
            }
        });
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patientId', 'patientId');
    }

    /**
     * Get the vitals for the patient.
     */
    public function vitals()
    {
        return $this->hasMany(Vital::class, 'patientId', 'patientId');
    }

    /**
     * Get the diagnoses for the patient.
     */
    public function dignosis()
    {
        return $this->hasMany(Diagnosis::class, 'patientId', 'patientId');
    }

    /**
     * Get the prescriptions for the patient.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patientId', 'patientId');
    }

    /**
     * Get the visitors for the patient.
     */

    public function visitors()
    {
         return $this->hasMany(Visitors::class, 'patientId', 'patientId');
    }
}
