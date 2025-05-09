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
            'type' => 'required|in:' . implode(',', PetType::values()),
            'breed' => 'required',
            'birth_date' => 'required|date',
            'weight' => 'required|numeric',
            'gender' => ['required', Rule::enum(PetGender::class)], // Assurez-vous que Gender::class existe
            'color' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_vaccinated' => 'boolean',
            'has_contagious_diseases' => 'boolean',
            'has_medical_file' => 'boolean',
            'is_critical_condition' => 'boolean',
            // Médias facultatifs (uniquement URL, type détecté côté backend)
                'media' => 'nullable|array',
                'media.*.media_url' => 'required_with:media|string|url',
                    ];
    }
}
