<?php

namespace App\Models\Patients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'patientId'; // Set primary key
    public $incrementing = false; // patientId is not auto-incrementing
    protected $keyType = 'string'; // patientId is a string

    // Define the fillable fields
    protected $fillable = [
        'firstName',
        'lastName',
        'dob',
        'gender',
        'phone',
        'email',
        'address',
        'purpose',
        'status',
        'nhis',
        'emgRelationship',
        'emgPhone'
    ];

    /**
     * Boot method for the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            // Fetch the last patient ID safely
            $lastPatient = self::latest('created_at')->first();
            $nextId = $lastPatient ? intval(substr($lastPatient->patientId, 2)) + 1 : 1;

            // Generate patientId
            $patient->patientId = 'P-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }


     /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patientId', 'patientId');
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class, 'patientId', 'patientId');
    }

    public function dignosis()
    {
        return $this->hasMany(Diagnosis::class, 'patientId', 'patientId');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patientId', 'patientId');
    }
}
