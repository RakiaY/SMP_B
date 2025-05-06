<?php

namespace App\Http\Controllers;

use App\Http\Requests\addPetRequest;
use App\Http\Requests\updatePetRequest;

use App\Http\Resources\PetResource;
use App\Enums\PetType;
use App\Enums\PetBreed;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\petMedia;
use App\Models\User;


class PetController extends Controller
{
   public function getPets(){
        $pets=Pet::all();
        return  PetResource::collection($pets);
   }
    public function getPetById($id){
          $pet=Pet::findOrFail($id);
          return new PetResource($pet);
    }

    public function addPet(addPetRequest $request , $owner_id){

        $owner = User::role('petowner')->findOrFail($owner_id);
        if (!$owner) {
            return response()->json(['msg' => 'donnez un id dun petOwner svp'], 404);
        }

        $data = $request->validated();
        $data['pet_owner_id'] = $owner->id;


        

        // 2. Vérification cohérence type/race
        $allowedBreeds = PetBreed::getBreedsByType($request->type);
        
        if (!in_array($request->breed, $allowedBreeds)) {
            return response()->json(['error' => 'Race invalide pour ce type'], 400);
        }
         
        // 3. Création
        $pet = Pet::create($data);

          // Ajout des médias si fournis
    foreach ($data['media'] ?? [] as $mediaItem) {
        $url = $mediaItem['media_url'];

        // Détection du type de média
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'photo' :
                (in_array($extension, ['mp4', 'webm', 'mov', 'avi']) ? 'video' : null);

        if ($type) {
            petMedia::create([
                'pet_id' => $pet->id,
                'media_patth' => $url, 
                'media_type' => $type,
                'is_thumbnail' => false,
                'uploaded_at' => now(),
            ]);
        }
    }
        // 4. Réponse
        return response()->json([
            'pet' => new PetResource($pet->load('petMedia')),
        ], 201);
        
      

     //

       

        
    }
    public function updatePet(updatePetRequest $request, $pet_id)
    {
        $pet = Pet::findOrFail($pet_id);
        $data = $request->validated();
    
        // Toujours valider la cohérence type / breed
        $type = $data['type'] ?? $pet->type;
        $breed = $data['breed'] ?? $pet->breed;
    
        $allowedBreeds = PetBreed::getBreedsByType($type);
        if (!in_array($breed, $allowedBreeds)) {
            return response()->json(['error' => 'Race invalide pour ce type d\'animal'], 400);
        }
    
        // Mise à jour
        $pet->update($data);
    
        return response()->json(['pet' => new PetResource($pet)]);
    }
    public function deletePet($pet_id)
    {
        $pet = Pet::findOrFail($pet_id);
        $pet->delete();
    
        return response()->json(['message' => 'Animal supprimé avec succès']);
    }    
    public function searchByTypeNameGender($type_name_gender){
        $pets= Pet::where('type', 'like', "%$type_name_gender%")
        ->orWhere('name', 'like', "%$type_name_gender%")
        ->orWhere('gender', 'like', "%$type_name_gender%")
        ->get();
        if ($pets->isEmpty()) {
            return response()->json(['message' => 'Aucun animal trouvé'], 404);
        }
        return  response()->json([
            'pets' => PetResource::collection($pets),
        ]);
    }
}
