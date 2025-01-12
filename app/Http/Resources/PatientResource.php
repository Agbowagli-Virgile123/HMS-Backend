<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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

            'patientId' => $this->patientId,
            'firstName' =>  $this->firstName,
            'lastName' =>  $this->lastName,
            'dob' =>  $this->dob,
            'gender' =>  $this->gender,
            'phone' =>  $this->phone,
            'email' =>  $this->email,
            'address' =>  $this->address,
            'purpose' =>  $this->purpose,
            'status' =>  $this->status,
            'nhis' =>  $this->nhis,
            'emgRelationship' =>$this->emgRelationship,
            'emgPhone' => $this->emgPhone,
        ];
    }
}
