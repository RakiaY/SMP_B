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
'gender' => ['required', Rule::in(Gender::values())],
    'phone' => 'required|string|min:8|max:20',
    'birth_date' => 'required|date|date_format:Y-m-d|before:today',
    'profilePictureURL' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    
     'password' => 'required|min:8|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
     'password_confirmation' => 'required|same:password',

    // Informations prooofessionnelles
    'experience' => 'nullable|string|max:500',
    'personalQualities' => 'nullable|string|max:500',
    'skills' => 'nullable|string|max:500',
    'ACACED' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

        // Adresse personnelle (obligatoire)
        'personal_address.city' => 'required|string|max:100',
        'personal_address.street' => 'required|string|max:255',
        'personal_address.zipcode' => 'required|string|max:20',

        // Adresse de chenil (optionnelle)
        'kennel_address.city' => 'nullable|string|max:100',
        'kennel_address.street' => 'nullable|string|max:255',
        'kennel_address.zipcode' => 'nullable|string|max:20',

   
];

    }
    public function messages(): array
{
    return [
        // Informations personnelles
        'first_name.required' => 'Le prénom est obligatoire',
        'first_name.min' => 'Le prénom doit contenir au moins 2 caractères',
        'last_name.required' => 'Le nom de famille est obligatoire',
        'email.required' => 'L\'email est obligatoire',
        'email.email' => 'Veuillez entrer une adresse email valide',
        'email.unique' => 'Cet email est déjà utilisé',
        'gender.required' => 'Le genre est obligatoire',
        'phone.required' => 'Le téléphone est obligatoire',
        'birth_date.required' => 'La date de naissance est obligatoire',
        'birth_date.before' => 'La date de naissance doit être dans le passé',
        
        // Mot de passe
        'password.required' => 'Le mot de passe est obligatoire',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre',
        'password_confirmation.same' => 'Les mots de passe ne correspondent pas',
        
        // Fichiers
        'profilePictureURL.mimes' => 'L\'image de profil doit être au format JPG, JPEG ou PNG',
        'profilePictureURL.max' => 'L\'image de profil ne doit pas dépasser 2Mo',
        'ACACED.required' => 'Le certificat ACACED est obligatoire',
        'ACACED.mimes' => 'Le fichier ACACED doit être au format PDF, JPG, JPEG ou PNG',
        'ACACED.max' => 'Le fichier ACACED ne doit pas dépasser 2Mo',
        
        // Adresses
        'personal_address.city.required' => 'La ville de l\'adresse personnelle est obligatoire',
        'personal_address.street.required' => 'La rue de l\'adresse personnelle est obligatoire',
        'personal_address.zipcode.required' => 'Le code postal de l\'adresse personnelle est obligatoire',
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
