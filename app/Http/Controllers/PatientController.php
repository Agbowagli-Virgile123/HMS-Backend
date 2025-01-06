<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
class PatientController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'patientId' => 'required|string|unique:patients,patientId',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'status' => 'required|string|in:pending,registered,discharged',
            'nhis' => 'nullable|string|max:50',
        ]);

        // Create a new patient
        $patient = Patient::create($validated);

        // Log::info('Patient created:', $patient->toArray());

        // Return a response
        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient,
        ], 201);
    }
}
