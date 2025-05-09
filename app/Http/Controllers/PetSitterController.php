<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Enums\Gender;
use App\Enums\UserStatut;
use App\Http\Resources\PetSitterResource;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\addPetSitterRequest;
use App\Http\Requests\addAdressRequest;



class PetSitterController extends Controller
{

    public function getPetSitters() {
        $petSitters = User::role('petsitter')->where('status', '!=', 'Deleted')->get();
        return response()->json([
            'success' => true,
            'petSitters' => PetSitterResource::collection($petSitters)
        ]);
    }
    public function getPetSitterById($id) {
        $petSitter = User::withTrashed()->role('petsitter')->findOrFail($id);
        return response()->json([
            'success' => true,
            'petSitter' => new PetSitterResource($petSitter),
            'created_at' => $petSitter->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $petSitter->updated_at->format('d/m/Y H:i:s'),
            'deleted_at' => $petSitter->deleted_at ? $petSitter->deleted_at->format('d/m/Y H:i:s') : null,
        ]);
    }
    public function addPetSitter(addPetSitterRequest $request)
{
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
    $petSitter->adresses()->create([
        'city' => $data['personal_address']['city'],
        'street' => $data['personal_address']['street'],
        'zipcode' => $data['personal_address']['zipcode'],
        'address_type' => 'personal',
    ]);

    // Ajouter l’adresse de chenil si fournie
    if (!empty($data['kennel_address']['city']) &&
        !empty($data['kennel_address']['street']) &&
        !empty($data['kennel_address']['zipcode'])) {

        $petSitter->adresses()->create([
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

    public function addAdress(addAdressRequest $request, $id) {
        $petSitter = User::role('petsitter')->findOrFail($id);

        $addresses = $request->validated()['addresses'];

        foreach ($addresses as $addressData) {
            Address::create([
                'user_id' => $petSitter->id,
                'address_type' => $addressData['address_type'],
                'city' => $addressData['city'],
                'street' => $addressData['street'],
                'zipcode' => $addressData['zipcode'],
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Adresses ajoutées avec succès',
            'Adress' => $petSitter->addresses
        ]);
        
    }
    public function updatePetSitter(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|min:2|max:50',
            'last_name' => 'sometimes|string|min:2|max:50',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone' => 'sometimes|string|max:15',
            'status' => [
                'sometimes',
                Rule::enum(UserStatut::class),
            ],
            'experience' => 'sometimes|string|max:255',
            'personalQualities' => 'sometimes|string|max:255',
            'skills' => 'sometimes|string|max:255',
            'profilePictureURL' => 'sometimes|url|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $data = $validator->validated();
    
        $petSitter = User::role('petsitter')->findOrFail($id);
    
        // S’il y a un mot de passe, il sera automatiquement hashé par le mutateur dans le modèle
        $petSitter->update($data);
    
        return response()->json([
            'success' => true,
            'message' => 'PetSitter mis à jour avec succès',
            'petSitter' => new PetSitterResource($petSitter),
        ]);
    }
    public function updatePetSitterStatut($sitter_id, Request $request){
        $validated = $request->validate([
            'status' => ['required', Rule::enum(UserStatut::class)],
        ]);
        $sitter = User::role('petsitter')->findOrfail($sitter_id);
        $sitter->status = $request->status;
        $sitter->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut de petSitter mis à jour avec succès',
            'status' => $sitter->status,
        ]);
    }
    
   
    public function deletePetSitter($id)  {
        $sitter = User::role('petsitter')->findOrfail($id);
        $sitter->status = UserStatut::Deleted->value;  //statut devient supprime
        $sitter->save();
        $sitter->delete();

        return response()->json([
            'success' => true,
            'message' => 'PetSitter supprimé avec succés'
        ], 200);   
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
    public function restorePetSitter($id){
        $admin = User::role('petsitter')->withTrashed()->findOrfail($id);
        $admin->restore();

        $admin->status = UserStatut::Active->value;  // statut retourne Active
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'PetSitter restauré avec succès',
            'admin' => new PetSitterResource($admin),
        ]);
    }

    public function forceDeletePetSitter($id) {
        $admin = User::role('petsitter')->withTrashed()->findOrfail($id);
        $admin->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'PetSitter supprimé définitivement'
        ]);
    }
   public function getByEmailPhoneOrName($emailphonename) {
        $petSitters = User::role('petsitter')
            ->where('email', 'like', '%' . $emailphonename . '%')
            ->orWhere('phone', 'like', '%' . $emailphonename . '%')
           ->orwhere('first_name', 'like', '%' . $emailphonename . '%')
            ->orWhere('last_name', 'like', '%' . $emailphonename . '%')
            
            ->get();
            if ($petSitters->isEmpty()) {
                return response()->json(['message' => 'Aucun PetSitter avec ces coordonnées'], 404);
            }

        return response()->json([
            'success' => true,
            'petSitter' => PetSitterResource::collection($petSitters)
        ]);
    }
public function getSitterByStatut($status) {
    $petSitters = User::withTrashed()         // Inclut les soft‑deleted
    ->role('petsitter')
        ->where('status', $status)
        ->get();

    if ($petSitters->isEmpty()) {
        return response()->json(['message' => 'Aucun PetSitter avec ce statut'], 404);
    }

    return response()->json([
        'petSitters' => PetSitterResource::collection($petSitters)
    ]);
    
}
}