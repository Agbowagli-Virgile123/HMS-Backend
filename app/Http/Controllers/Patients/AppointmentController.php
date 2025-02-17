<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentRessource;
use App\Models\Users\Department;
use Illuminate\Http\Request;
use App\Models\Users\User;
use App\Models\Patients\Appointment;
use App\Models\Patients\Patient;
use Carbon\Carbon;
use App\Services\SMSService;
use App\Mail\AppointmentBookedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller{

//All Appointments
    public function index()
    {
        // Retrieve all appointments
        // $appointments = Appointment::all();
        $appointments = Appointment::with(['patient','user'])->orderBy('created_at','desc') ->get();

        // foreach($appointments as $appointment){
        //     $patientName = $appointment->patient->firstName;
        // }
        if ($appointments->isEmpty()) {
            return response()->json([
                'message' => 'No appointments found.',
                'appointments' => []]);
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
            'purpose' => 'required|in:checkup,heart and vascular treatments,vaccination,consultation,conseilling',
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

        // Get patient details
        $patient = Patient::where('patientId', $patientId)->first();
        if (!$patient) {
            return response()->json(['error' => 'Patient not found.'], 404);
        }

        // Format phone number for Ghana (Textbelt needs numbers in international format)
        $phoneNumber = '233' . ltrim($patient->phone, '0'); // Convert local format to international

        // Get the corresponding department based on the purpose
        $department = Department::where('purpose', $purpose)->first();
        if (!$department) {
            return response()->json(['error' => 'No department found for this purpose.'], 404);
        }

        // Get the corresponding department based on the purpose
        $department = Department::where('purpose', $purpose)->first();

        if (!$department) {
            return response()->json(['error' => 'No department found for this purpose.'], 404);
        }

        // Find an available employee in the department for the appointment date
        $dayOfWeek = date('l', strtotime($appointmentDate));


        // Find an employee with a schedule on this day
        $availableEmployee = User::where('department_id', $department->id)
            ->whereHas('employeeSchedules', function ($query) use ($dayOfWeek) {
                $query->where('day_of_week', $dayOfWeek);
            })
            ->first();

        if (!$availableEmployee) {
            return response()->json(['error' => 'No employee is available on this date.'], 404);
        }

        //dd($availableEmployee);


        // Get the employee's schedule to assign the appointment time
        $schedule = $availableEmployee->employeeSchedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            return response()->json(['error' => 'No schedule found for the employee on this day.'], 404);
        }

        // Get the employee's available start time and end time for the appointment
        $appointmentStartTime = Carbon::parse($schedule->appointment_start_time);
        $appointmentEndTime = Carbon::parse($schedule->appointment_end_time);

        // Get the number of minutes for the appointment from the department
        $appointmentDuration = $department->number_of_minute_for_appointment;

        // Check if there are existing appointments on the selected date and time
        $existingAppointments = Appointment::where('employeeId', $availableEmployee->employeeId)
            ->whereDate('appointmentDate', $appointmentDate)
            ->get();

        $newAppointmentStartTime = $appointmentStartTime;

        // Check if there is an existing appointment that conflicts
        foreach ($existingAppointments as $existingAppointment) {
            $existingEndTime = Carbon::parse($existingAppointment->appointmentTime)->addMinutes($department->number_of_minute_for_appointment);

            if ($newAppointmentStartTime->lt($existingEndTime)) {
                // If there is a conflict, set the new appointment time after the last booked appointment
                $newAppointmentStartTime = $existingEndTime;
            }
        }

        // Ensure the new appointment time does not exceed the appointment end time
        if ($newAppointmentStartTime->gt($appointmentEndTime)) {
            return response()->json(['error' => 'No available time slots for the selected appointment date.'], 400);
        }

        // Set the new appointment time
        $newAppointmentEndTime = $newAppointmentStartTime->copy()->addMinutes($appointmentDuration);


        // Create the appointment
        $appointment = Appointment::create([
            'patientId' => $patientId,
            'employeeId' => $availableEmployee->employeeId,
            'department_id' => $department->id,
            'bookerId' => $bookerId,
            'purpose' => $purpose,
            'appointmentDate' => $appointmentDate,
            'appointmentTime' => $newAppointmentStartTime->toTimeString(),
            'status' => "scheduled",
        ]);


         // Send Email Notification
        //Mail::to($availableEmployee->email)->send(new AppointmentBookedMail($appointment));

        //Mail::to($patient->email)->send(new AppointmentBookedMail($appointment));


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

        $patient = Patient::find($patientId);

        if(!$patient){
            return response()->json([
                'error' => 'Invalid patient Id'
            ],404);
        }

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
        $employee = User::find($employeeId);

        if(!$employee){
            return response()->json([
                'error' => 'Invalid employee Id'
            ],404);
        }

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

