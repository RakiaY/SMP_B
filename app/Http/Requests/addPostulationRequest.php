<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addPostulationRequest extends FormRequest
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
            'sitter_id' => 'required|exists:users,id',
        'search_id' => 'required|exists:search_pet_sitters,id',
        'statut' => 'required|string|in:en_attente,acceptee,refusee',
            //
        ];
    }
}
