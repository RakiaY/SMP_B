<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
class addPetSitterRequest extends FormRequest
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
    // Informations personnelles
    'first_name' => 'required|string|min:2|max:50',
    'last_name' => 'required|string|min:2|max:50',
    'email' => 'required|email|unique:users,email|max:255',
    'gender' => ['required', Rule::enum(Gender::class)], // Assurez-vous que Gender::class existe
    'phone' => 'required|string|min:8|max:20',
    'birth_date' => 'required|date|date_format:Y-m-d|before:today',

    // Informations supplémentaires
    'experience' => 'nullable|string|max:500',
    'personalQualities' => 'nullable|string|max:500',
    'skills' => 'nullable|string|max:500',

    // Fichiers
    'profilePictureURL' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    'ACACED' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',

    // Sécurité
    'password' => 'required|min:8|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
    'password_confirmation' => 'required|same:password',
];

    }
    protected function failedValidation(Validator $validator)
{
    throw new HttpResponseException(response()->json([
        'success' => false,
        'message' => 'Validation errors',
        'errors' => $validator->errors()
    ], 422));
}
}
