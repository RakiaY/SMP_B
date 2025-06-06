<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetSitterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender, 
 

            'status' => $this->status,
            'experience' => $this->experience,
            'personalQualities' => $this->personalQualities,
            'skills' => $this->skills,
            'profilePictureURL' => $this->profilePictureURL,
            'ACACED' => $this->ACACED ? asset('storage/' . $this->ACACED) : null,


            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
             'personal_address' => [
            'city' => $this->personalAddress->city ?? '',
            'street' => $this->personalAddress->street ?? '',
            'zipcode' => $this->personalAddress->zipcode ?? '',
        ],
        'kennel_address' => [
            'city' => $this->kennelAddress->city ?? '',
            'street' => $this->kennelAddress->street ?? '',
            'zipcode' => $this->kennelAddress->zipcode ?? '',
        ],
        ];
    }
}
