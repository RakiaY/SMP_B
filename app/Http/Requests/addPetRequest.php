<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PetGender;
use App\Enums\PetType;
use Illuminate\Validation\Rule;

class addPetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Vérifiez que l'utilisateur a le rôle "petowner"
            'pet_owner_id' => 'required|exists:users,id',
    'name' => 'required|string|max:255',
    'type' => 'required|string|max:255',
    'breed' => 'required|string|max:255',
    'birth_date' => 'required|date',
    'weight' => 'required|numeric',
    'gender' => 'required|string',
    'color' => 'required|string',
    'description' => 'nullable|string',
    'is_vaccinated' => 'required|boolean',
    'has_contagious_diseases' => 'required|boolean',
    'has_medical_file' => 'required|boolean',
    'is_critical_condition' => 'required|boolean',
    'media.*' => 'file|mimes:jpg,jpeg,png,mp4|max:10240' // 10MB max
        ];
    
}
}
