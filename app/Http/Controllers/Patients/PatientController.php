<?php

namespace App\Http\Controllers\Patients;

use Illuminate\Http\Request;
use App\Models\Patients\Patient;
use App\Http\Resources\PatientResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
class PatientController extends Controller
{

    public function allPatients(Request $request)
    {
        // Get the number of items per page from the request, default to 10
        $perPage = $request->input('per_page', 10);

        // Fetch paginated patients, ordered by the latest update
        $patients = Patient::latest('updated_at')->simplePaginate($perPage);

        // Check if any patients exist
        if ($patients->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Patients retrieved successfully',
                'data' => $patients,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No records available',
                'data' => [],
            ], 200);
        }
    }



    public function pendiengPatients(Request $request)
    {
        // Get the number of items per page from the request, default to 10
        $perPage = $request->input('per_page', 10);

        // Define the statuses to filter
        $statuses = ['pending'];

        // Fetch patients with the specified statuses and apply pagination
        $patients = Patient::whereIn('status', $statuses)
            ->latest('updated_at')
            ->simplePaginate($perPage);

        // Check if any patients exist
        if ($patients->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Patients retrieved successfully',
                'data' => $patients,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No records available',
                'data' => [],
            ], 200);
        }
    }



    public function outPatients(Request $request)
    {
        // Get the number of items per page from the request, default to 10
        $perPage = $request->input('per_page', 10);

        // Define the statuses to filter
        $statuses = ['discharged', 'transferred', 'deseased'];

        // Fetch patients with the specified statuses and apply pagination
        $patients = Patient::whereIn('status', $statuses)
            ->latest('updated_at')
            ->simplePaginate($perPage);

        // Check if any patients exist
        if ($patients->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Patients retrieved successfully',
                'data' => $patients,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No records available',
                'data' => [],
            ], 200);
        }
    }



    public function inPatients(Request $request)
    {
        // Get the number of items per page from the request, default to 10
        $perPage = $request->input('per_page', 10);

        // Define the statuses to filter
        $statuses = ['registered', 'admitted'];

        // Fetch patients with the specified statuses and apply pagination
        $patients = Patient::whereIn('status', $statuses)
            ->latest('updated_at')
            ->simplePaginate($perPage);

        // Check if any patients exist
        if ($patients->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Patients retrieved successfully',
                'data' => $patients,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No records available',
                'data' => [],
            ], 200);
        }
    }


    public function store(Request $request)
    {
        // Validate the request data
        $validated = Validator::make($request->all(),[
            // 'patientId' => 'required|string|unique:patients,patientId',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'purpose' => 'required|string|in:Medication,Appointment Booking,Follow-up,Other',
            'status' => 'required|string|in:pending,registered,discharged,transferred,admitted,deseased',
            'nhis' => 'nullable|string|max:50',
            'emgRelationship' =>'required|string|in:parent,spouse,sibling,other',
            'emgPhone' => 'required|string'
        ]);

        // Create a new patient
        $patient = Patient::create([

            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'dob' => $request['dob'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'address' => $request['address'],
            'purpose' => $request['purpose'],
            'status' => $request['status'],
            'nhis' => $request['nhis'],
            'emgRelationship' =>$request['emgRelationship'],
            'emgPhone' => $request['emgPhone'],
        ]);

        // Log::info('Patient created:', $patient->toArray());

        // Return a response
        return response()->json([
            'message' => 'Patient created successfully',
            // 'patient' => $patient,
            'patient' => new PatientResource($patient),
        ], 201);
    }


    public function show($patientId){

        $patient = Patient::where('patientId', $patientId)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient with the given ID does not exist.',
                'errors' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $patient,
        ]);
            
    }

    public function update(Request $request,Patient $patient){
        // Validate the request data
        $validated = Validator::make($request->all(),[
            // 'patientId' => 'required|string|unique:patients,patientId',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'status' => 'required|string|in:pending,registered,discharged',
            'nhis' => 'nullable|string|max:50',
            'emgRelationship' =>'required|string|in:parent,spouse,sibling,other',
            'emgPhone' => 'required|string'
        ]);

        // Update the patient
        $patient->update([

            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'dob' => $request['dob'],
            'gender' => $request['gender'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'address' => $request['address'],
            'purpose' => $request['purpose'],
            'status' => $request['status'],
            'nhis' => $request['nhis'],
            'emgRelationship' =>$request['emgRelationship'],
            'emgPhone' => $request['emgPhone'],
        ]);

        // Log::info('Patient created:', $patient->toArray());

        // Return a response
        return response()->json([
            'message' => 'Patient updated successfully',
            // 'patient' => $patient,
            'patient' => new PatientResource($patient),
        ], 201);
    }

    public function destroy(Patient $patient){
        $patient->delete();
        return response()->json([
            'message' => 'Patient Deleted Successfully',
        ], 200);
    }
}
