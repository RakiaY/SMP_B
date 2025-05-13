<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostulationResource extends JsonResource
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
    'sitter_id' => $this->sitter_id,
    'search_id' => $this->search_id,
  // a refaire 
  // 'owner_name' => $this->search && $this->search->user? $this->search->user->first_name . ' ' . $this->search->user->last_name: null,

    'statut' => $this->statut,
    'created_at' => $this->created_at,
    'updated_at' => $this->updated_at,      
];

    }
}
