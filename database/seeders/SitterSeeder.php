<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SitterSeeder extends Seeder
{
    public function run()
    {
        $lastNames = [
            'Farhat', 'Najjar', 'Bouazizi', 'Riahi', 'Khlifi',
            'Mansour', 'Zouari', 'Toumi', 'Hmidet', 'Karray'
        ];
        $genders = ['Male', 'Female'];

        for ($i = 1; $i <= 10; $i++) {
            // Détermination aléatoire du genre parmi les valeurs autorisées
            $gender = $genders[($i - 1) % 2];

            // Génération d’une date de naissance valide (format Y-m-d, avant aujourd’hui, entre 25 et 40 ans)
            $birthDate = Carbon::now()
                ->subYears(rand(25, 40))
                ->subDays(rand(0, 365))
                ->format('Y-m-d');

            // Création de l’utilisateur Sitter avec uniquement les champs "required"
            $user = User::create([
                // Informations personnelles (obligatoires)
                'first_name'            => 'sitter' . $i,                          // string, min:2, max:50
                'last_name'             => $lastNames[$i - 1],                     // string, min:2, max:50
                'email'                 => "sitter{$i}@example.com",               // email, unique, max:255
                'gender'                => $gender,                                 // in [Male, Female]
                'phone'                 => '22' . rand(10000000, 99999999),         // string, min:8, max:20
                'birth_date'            => $birthDate,          
                'staus'             => 'Active',                                // string, in [Active, Inactive, Suspended]
                       // date, before:today

                // Mot de passe (obligatoire) + confirmation (même valeur)
                'password'              => 'Sitter123',                 // respecte regex: 1 maj, 1 min, 1 chiffre
                'password_confirmation' => 'Sitter123',                             // same:password

                // Adresse personnelle (obligatoire) sous forme de colonne "personal_address"
                // Cela suppose que votre modèle User possède des colonnes :
                // personal_address_city, personal_address_street, personal_address_zipcode
                // ou un cast JSON/array. Ici on assigne directement dans les colonnes.
                'personal_address_city'    => 'Tunis',
                'personal_address_street'  => '10 Rue de la Paix',
                'personal_address_zipcode' => '1001',
                
                // Les champs suivants sont optionnels ou nullable selon votre Request, 
                // on ne les renseigne donc pas ici :
                // 'profilePictureURL' => null,
                // 'experience'        => null,
                // 'personalQualities' => null,
                // 'skills'            => null,
                // 'ACACED'            => null,
                // 'kennel_address_city'    => null,
                // 'kennel_address_street'  => null,
                // 'kennel_address_zipcode' => null,
            ]);

            // Attribution du rôle "petsitter" (Spatie ou équivalent)
            $user->assignRole('petsitter');
        }
    }
}
