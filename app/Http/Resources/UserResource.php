<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);

        return([

            'employeeId' => $this->employeeId,
            'role' => $this->role->roleName,
            'department' => $this->department->departmentName,
            'fullName' => $this->firstName.' '.$this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ]);
    }
}


