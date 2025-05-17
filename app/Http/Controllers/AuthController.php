<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\LoginResource;
use App\Http\Resources\PetOwnerResource;
use App\Http\Resources\PetSitterResource;
use App\Http\Requests\addPetOwnerRequest;
use App\Http\Requests\addPetSitterRequest;
use App\Enums\UserStatut;

use App\Http\Requests\addAdressRequest;


class AuthController extends Controller
    {
   

    
   
public function Adminlogin(LoginRequest $request)
{
    $data = $request->validated();

    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Identifiants incorrects.',
        ], 401);
    }

    // Vérifier si c’est un admin ou superadmin
    if (!$user->hasRole('admin') && !$user->hasRole('super_admin')) {
        return response()->json([
            'success' => false,
            'message' => 'Refused access. Only admins or superadmins can log in.',
        ], 403);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
         'success' => true,
        'user_information' => new LoginResource($user),
        'token' => $token,
        'message' => 'Admin logged in successfully'
    ]);
}
public function Userlogin(LoginRequest $request)
{
    $data = $request->validated();

    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Identifiants incorrects.',
        ], 401);
    }

    if (!$user->hasRole('petowner') && !$user->hasRole('petsitter')) {
        return response()->json([
            'success' => false,
            'message' => 'Refused access. ',
        ], 403);
    }

    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'success' => true,

        'user_information' => new LoginResource($user),
        'token' => $token,

        'message' => 'User logged in successfully'
    ]);
}

public function registerPetOwner(addPetOwnerRequest $request) {
        $data = $request->validated();
        //status actif automatiquement des la creation
        $data['status'] = UserStatut::Active->value;
        $petowner = User::create($data);

        //attribuer le role:
        $petowner->assignRole('petowner');

        //affichage 
        return response()->json([
            'success' => true,
            'message' => 'PetOwner ajouté avec succès',
            'petowner' => new PetOwnerResource($petowner)
        ]);
}

    // Enregistrement d'un pet-sitter
public function registerPetSitter(addPetSitterRequest $request){
        $data = $request->validated();
        // Gérer le fichier
        if ($request->hasFile('ACACED')) {
            $file = $request->file('ACACED');
            $path = $file->store('ACACED', 'public'); // stocke dans storage/app/public/justificatifs
            $data['ACACED'] = $path;
        }

        // Définir le statut actif
        $data['status'] = UserStatut::Active->value;

        // Créer le pet-sitter
        $petSitter = User::create($data);

        // Attribuer le rôle
        $petSitter->assignRole('petsitter');

        // Ajouter l’adresse personnelle
        $petSitter->personalAddress()->create([
            'city' => $data['personal_address']['city'],
            'street' => $data['personal_address']['street'],
            'zipcode' => $data['personal_address']['zipcode'],
            'address_type' => 'personal',
        ]);

        // Ajouter l’adresse de chenil si fournie
        if (!empty($data['kennel_address']['city']) &&
            !empty($data['kennel_address']['street']) &&
            !empty($data['kennel_address']['zipcode'])) {

            $petSitter->kennelAddress()->create([
                'city' => $data['kennel_address']['city'],
                'street' => $data['kennel_address']['street'],
                'zipcode' => $data['kennel_address']['zipcode'],
                'address_type' => 'kennel',
            ]);
        }

        // Réponse
        return response()->json([
            'success' => true,
            'message' => 'PetSitter ajouté avec succès',
            'petSitter' => new PetSitterResource($petSitter)
        ]);
}

    protected function createTokenForUser($user){
            $token = $user->createToken('auth_token')->plainTextToken;
                return $token;
}

public function logout(Request $request){
            //$request->user()->currentAccessToken()->delete();
                // pour se deconnecter de tous les appareils
                $request->user()->tokens()->delete(); 

                return response()->json([
                    'success' => true,
                    'message' => 'User logged out successfully'
                ]);
}


}
