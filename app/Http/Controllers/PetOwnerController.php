<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\addPetOwnerRequest;
use App\Http\Requests\updatePetOwnerRequest;

use App\Models\User;
use App\Enums\Gender;
use App\Enums\UserStatut;
use App\Http\Resources\PetOwnerResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;




class PetOwnerController extends Controller
{
    public function getPetOwners() {
        $petOwners = User::role('petowner')->where('status', '!=', 'Deleted')->get();
        return response()->json([
            'success' => true,
            'message' => 'Liste des petowners récupérée avec succès',
            'petOwners' => PetOwnerResource::collection($petOwners)
        ]);
    }

    public function getPetOwnerById($id) {
        $petOwner = User::withTrashed()->role('petowner')->findOrFail($id);
        return response()->json([
            'success' => true,
            'petOwner' => new PetOwnerResource($petOwner),
            'created_at' => $petOwner->created_at->format('d/m/Y H:i:s'),
        'updated_at' => $petOwner->updated_at->format('d/m/Y H:i:s'),
        'deleted_at' => $petOwner->deleted_at ? $petOwner->deleted_at->format('d/m/Y H:i:s') : null,
        ]);
    }
    

    public function addPetOwner(addPetOwnerRequest $request) {
        $data = $request->validated();
        //status actif automatiquement des la creation
        $data['status'] = UserStatut::Active->value;
        $petowner = User::create($data);


        //lui attribuer le role:
        $petowner->assignRole('petowner');

        //affichage 
        return response()->json([
            'success' => true,
            'message' => 'PetOwner ajouté avec succès',
            'petowner' => new PetOwnerResource($petowner)
        ]);
    }

    public function updatePetOwner(Request $request, $id){
    $validator = Validator::make($request->all(), [
        'first_name' => 'sometimes|string|min:2|max:50',
        'last_name' => 'sometimes|string|min:2|max:50',
        'email' => [
            'sometimes',
            'email',
            'max:255',
            Rule::unique('users', 'email')->ignore($id),
        ],
        'password' => [
            'sometimes',
            'nullable',
            'min:8',
            'max:64',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ],
        'password_confirmation' => 'nullable|same:password',
       
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $data = $validator->validated();

    $petowner = User::role('petowner')->findOrFail($id);

    // S’il y a un mot de passe, il sera automatiquement hashé par le mutateur dans le modèle
    $petowner->update($data);

    return response()->json([
        'success' => true,
        'message' => 'PetOwner mis à jour avec succès',
        'petowner' => new PetOwnerResource($petowner),
    ]);
}
    
public function updatePetOwnerStatut($owner_id, Request $request){
        $validated = $request->validate([
            'status' => ['required', Rule::enum(UserStatut::class)],
        ]);
        $owner = User::role('petowner')->findOrfail($owner_id);
        $owner->status = $request->status;
        $owner->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut de petowner mis à jour avec succès',
            'status' => $owner->status,
        ]);

    
       
    }
    public function deletePetOwner($id)  {
        $owner = User::role('petowner')->findOrfail($id);
        $owner->status = UserStatut::Deleted->value;  //statut devient supprime
        $owner->save();
        $owner->delete();

        return response()->json([
            'success' => true,
            'message' => 'PetOwner supprimé avec succés'
        ], 200);   
}
public function restorePetOwner($id){
        $owner = User::role('petowner')->withTrashed()->findOrfail($id);
        $owner->restore();

        $owner->status = UserStatut::Active->value;  // statut retourne Active
        $owner->save();

        return response()->json([
            'success' => true,
            'message' => 'PetOwner restauré avec succès',
            'admin' => new PetOwnerResource($owner),
        ]);
}
public function forceDeletePetOwner($id) {
        $owner = User::role('petowner')->withTrashed()->findOrfail($id);
        $owner->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'PetOwner supprimé définitivement'
        ]);
    }

    public function getOwnerByEmailOrName($email_or_name)
    {
        
        $petOwners = User::role('petowner')
            ->where(function ($query) use ($email_or_name) {
                $query->where('email', 'LIKE', "%{$email_or_name}%")
                      ->orWhere('first_name', 'LIKE', "%{$email_or_name}%")
                      ->orWhere('last_name', 'LIKE', "%{$email_or_name}%");
                      ;
            })
            ->get();
    
        if ($petOwners->isEmpty()) {
            return response()->json(['message' => 'Aucun PetOwner avec ces coordonnées'], 404);
        }
    
        return response()->json([
            'petOwners' => PetOwnerResource::collection($petOwners),
        ]);
    }
public function getOwnerByStatut($status)
    {
        $petOwners = User::withTrashed()         // Inclut les soft‑deleted
        ->role('petowner')
        ->where('status', $status)
        ->get();

        if ($petOwners->isEmpty()) {
            return response()->json(['message' => 'Aucun PetOwner avec ce statut'], 404);
        }

        return response()->json([
            'petOwners' => PetOwnerResource::collection($petOwners),
        ]);
    }

    

   

}
