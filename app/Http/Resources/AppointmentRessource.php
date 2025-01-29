<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return[
            'appointmentId' => $this->id,
            'employeeId' => $this->employeeId,
            'employeeFirstname' => $this->user->firstName,
            'employeeLastname' => $this->user->lastName,
            'departmentName' => $this->department->departmentName,
            'patientId' => $this->patientId,
            'patientFirstname' => $this->patient->firstName,
            'patientLastname' => $this->patient->lastName,
            'bookerId' => $this->bookerId,
            'purpose' => $this->purpose,
            'appointmentDate' => $this->appointmentDate,
            'appointmentTime' => $this->appointmentTime,
            'status' => $this->status,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
