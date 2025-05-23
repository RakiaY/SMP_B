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
        'search_id' => 'required|exists:search_pet_sitters,id',
        'pet_sitter_ids' => 'required|array|min:1',
        'pet_sitter_ids.*' => 'exists:users,id',
        //'statut' => 'required|string|in:en_attente,acceptée, validée, en cours, terminée,annulée',
            //
        ];
    }
}
