<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Psy\Exception\BreakException;

use function Symfony\Component\String\b;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            
            'id'         => $this->id,
            'pet_owner_id' => $this->pet_owner_id,
            'name'       => $this->name,
            'type'    => $this->type,
            'breed'        => $this->breed,
            'gender'        => $this->gender,
            'birth_date'         => $this->birth_date,
            'weight'        => $this->weight,
            'color'        => $this->color,
            'description'        => $this->description,
            'is_vaccinated'        => $this->is_vaccinated,
            'has_contagious_diseases'        => $this->has_contagious_diseases,
            'has_medical_file'        => $this->has_medical_file,
            'is_critical_condition'        => $this->is_critical_condition,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
             // 👇 Ajout des médias
             'medias' => $this->whenLoaded('petMedias', function () {
    return $this->petMedias->map(function ($media) {
        return [
            'media_url' => $media->media_patth,
            'media_type' => $media->media_type,
            'is_thumbnail' => $media->is_thumbnail,
            'uploaded_at' => $media->uploaded_at,
        ];
    });
}),




            
        ];
    }
    
}
