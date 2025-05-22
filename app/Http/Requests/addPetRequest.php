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
            'name' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'weight' => 'nullable|numeric',
            'taille' => 'nullable|in:Petite,Moyenne,Grande',
            'gender' => 'nullable|string',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'is_vaccinated' => 'nullable|boolean',
            'has_contagious_diseases' => 'nullable|boolean',
            'has_medical_file' => 'nullable|boolean',
            'is_critical_condition' => 'nullable|boolean',
             // photo de profil
            'photo_profil' => 'sometimes|file|mimes:jpg,jpeg,png,svg|max:10240',
            'media.*' => 'sometimes|file|mimes:jpg,jpeg,png,svg,mp4|max:10240'
                ];
    

}
}