<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\PetGender;
use App\Enums\PetType;

class UpdatePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // À adapter selon logique d’autorisation
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'type' => ['sometimes', Rule::in(PetType::values())],
            'breed' => 'sometimes|string',
            'gender' => ['sometimes', Rule::enum(PetGender::class)], // Assurez-vous que Gender::class existe
            'birth_date' => 'sometimes|date',
            'weight' => 'sometimes|numeric',
            'color' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_vaccinated' => 'sometimes|boolean',
            'has_contagious_diseases' => 'sometimes|boolean',
            'has_medical_file' => 'sometimes|boolean',
            'is_critical_condition' => 'sometimes|boolean',
            
        ];
    }
}
