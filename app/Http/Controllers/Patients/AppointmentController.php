<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentRessource;
use App\Models\Users\Department;
use Illuminate\Http\Request;
use App\Models\Users\User;
use App\Models\Patients\Appointment;
use App\Models\Patients\Patient;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller{

//All Appointments
    public function index()
    {
        // Retrieve all appointments
        // $appointments = Appointment::all();
        $appointments = Appointment::with(['patient','user'])->get();

        // foreach($appointments as $appointment){
        //     $patientName = $appointment->patient->firstName;
        // }

        if ($appointments->isEmpty()) {
            return response()->json([
                'message' => 'No appointments found.'],
                404);
        }


        return response()->json([
            'appointment' => AppointmentRessource::collection($appointments),

        ]);
    }


    // Creating an appointment
    public function store(Request $request)
    {
        // Validate request data
        $validated = Validator::make($request->all(), [
            'patientId' => 'required|exists:patients,patientId',
            'bookerId' => 'required|string',
            'purpose' => 'required|in:checkup',
            'appointmentDate' => 'required|date',
            'status' => 'required|in:pending,scheduled,completed,cancelled,checked-in',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()], 400);
        }



        $patientId = $request['patientId'];
        $bookerId = $request['bookerId'];
        $purpose = $request['purpose'];
        $appointmentDate = $request['appointmentDate'];
        $status = $request['status'];


        // Get the corresponding department based on the purpose
        $department = Department::where('purpose', $purpose)->first();

        if (!$department) {
            return response()->json(['error' => 'No department found for this purpose.'], 404);
        }

        // Find an available employee in the department for the appointment date
        $dayOfWeek = date('l', strtotime($appointmentDate));

        // dd([
        //     'department' => $department,
        //     'dayOfWeek' => $dayOfWeek,
        //     'availableEmployees' => User::where('department_id', $department->id)
        //         ->whereHas('employeeSchedules', function ($query) use ($dayOfWeek) {
        //             $query->where('day_of_week', $dayOfWeek);
        //         })
        //         ->get()
        // ]);

        $availableEmployee = User::where('department_id', $department->id)
            ->whereHas('employeeSchedules', function ($query) use ($dayOfWeek) {
                $query->where('day_of_week', $dayOfWeek);
            })
            ->first();

        if (!$availableEmployee) {
            return response()->json(['error' => 'No employee is available on this date.'], 404);
        }



        // Get the employee's schedule to assign the appointment time
        $schedule = $availableEmployee->employeeSchedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'No schedule found for the employee on this day.'], 404);
        }

        $appointmentTime = $schedule->start_time;


        // Create the appointment
        $appointment = Appointment::create([
            'patientId' => $patientId,
            'employeeId' => $availableEmployee->employeeId,
            'department_id' => $department->id,
            'bookerId' => $bookerId,
            'purpose' => $purpose,
            'appointmentDate' => $appointmentDate,
            'appointmentTime' => $appointmentTime,
            //'status' => $status,
            'status' => "scheduled",
        ]);

        return response()->json([
            'message' => 'Appointment successfully created.',
            'appointment' => $appointment,
        ]);
    }


 /**
     * Show an appointment by its ID.
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        return response()->json(['appointment' => $appointment]);
    }

    /**
     * Update an appointment by its ID.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        // Validate request data
        $validated = $request->validate([
            'appointmentDate' => 'required|date',
            'status' => 'required|in:completed,cancelled,checked-in',
            'purpose' => 'required|string',
        ]);

        // Update appointment fields
        $appointment->update($validated);

        return response()->json(['message' => 'Appointment status updated successfully.', 'appointment' => $appointment]);
    }


     /**
     * Update an appointment status by its ID.
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        // Validate request data
        $validated = $request->validate([
            'status' => 'required|in:completed,cancelled,checked-in',
        ]);

        // Update appointment fields
        $appointment->update($validated);

        return response()->json(['message' => 'Appointment status updated successfully.', 'appointment' => $appointment]);
    }

    /**
     * Delete an appointment by its ID.
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found.'], 404);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully.']);
    }

    /**
     * Get appointments by patientId.
     */
    public function getAppointmentsByPatient($patientId)
    {
        $appointments = Appointment::where('patientId', $patientId)->get();

        if ($appointments->isEmpty()) {
            return response()->json(['error' => 'No appointments found for this patient.'], 404);
        }

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Get appointments by employeeId.
     */
    public function getAppointmentsByEmployee($employeeId)
    {
        $appointments = Appointment::where('employeeId', $employeeId)->get();

        if ($appointments->isEmpty()) {
            return response()->json(['error' => 'No appointments found for this employee.'], 404);
        }

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Get appointments by patientId and employeeId.
     */
    public function getAppointmentsByPatientAndEmployee($patientId, $employeeId)
    {
        $appointments = Appointment::where('patientId', $patientId)
            ->where('employeeId', $employeeId)
            ->get();

        if ($appointments->isEmpty()) {
            return response()->json(['error' => 'No appointments found for this patient and employee.'], 404);
        }

        return response()->json(['appointments' => $appointments]);
    }

}

