<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return [
            'visitorId' => $this->id,
            'patientId' => $this->patientId,
            'fullName' => $this->fullName,
            'patientName' => $this->patient->firstName.' '.$this->patient->lastName,
            'address' => $this->address,
            'phone' => $this->phone,
            'numOfPeople' => $this->numOfPeople,
            'relationship' => $this->relationship,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
