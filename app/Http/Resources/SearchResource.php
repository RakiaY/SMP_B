<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
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
            'user_id' => $this->user_id,
            'pet_id' => $this->pet_id,
            'user_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'pet_name' => $this->pet->name,
                        'pet_type' => $this->pet->type,


                'adresse' => $this->adresse,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'description' => $this->description,
                'care_type' => $this->care_type, 
                'care_duration' => $this->care_duration,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'expected_services' => $this->expected_services, //( marche;nourrissag; toilettage)
                'remunerationMin' => $this->remunerationMin,
                'remunerationMax' => $this->remunerationMax,
        ];
    }
}
