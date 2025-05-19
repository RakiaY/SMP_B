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
use Illuminate\Support\Facades\Auth;


class PetController extends Controller
{
   public function getPets(){
         $pets = Pet::with('user')->get();
        return  response()->json([
            'Pets' => PetResource::collection($pets),
        ]);
   }
    public function getPetById($id){
        $pet = Pet::with(['user', 'petMedia'])->findOrFail($id);
        return response()->json([
    'pet' => new PetResource($pet),
]);
    }
    // PetController.php
public function getPetsByOwner($id)
{
    // Vérification si l'utilisateur a le rôle 'petowner'
    $owner = User::role('petowner')->findOrFail($id);
    $pets = Pet::with('user')->where('pet_owner_id', $id)->get();
            return  response()->json([
            'Pets' => PetResource::collection($pets),
        ]);}


  public function addPet(addPetRequest $request){
    //\Log::info('Current user:', ['user' => Auth::user()]);
    //$owner = User::role('petowner')->findOrFail($request->pet_owner_id);

    // Vérification si l'utilisateur a le rôle 'petowner'
    // On utilise Auth::user() pour obtenir l'utilisateur authentifié
    $owner = Auth::user();
    if (!$owner->hasRole('petowner')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $request->merge([
        'pet_owner_id' => Auth::user()->id, // force correct ID
        'is_vaccinated' => filter_var($request->is_vaccinated, FILTER_VALIDATE_BOOLEAN),
        'has_contagious_diseases' => filter_var($request->has_contagious_diseases, FILTER_VALIDATE_BOOLEAN),
        'has_medical_file' => filter_var($request->has_medical_file, FILTER_VALIDATE_BOOLEAN),
        'is_critical_condition' => filter_var($request->is_critical_condition, FILTER_VALIDATE_BOOLEAN),
    ]);

    $data = $request->validated();

    $allowedBreeds = PetBreed::getBreedsByType($data['type']);
    if (!in_array($data['breed'], $allowedBreeds)) {
        return response()->json(['error' => 'Race invalide pour ce type'], 400);
    }

    $pet = Pet::create($data);

    // Handle media
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $path = $file->store('pet_medias', 'public');

            PetMedia::create([
                'pet_id' => $pet->id,
                'media_patth' => $path,
                'media_type' => $this->getMediaType($file->getMimeType()),
                'is_thumbnail' => false,
                'uploaded_at' => now(),
            ]);
        }
    }

    return response()->json(['pet' => new PetResource($pet)], 201);
}


/**
 * Détermine le type de média en fonction du mimeType du fichier.
 *
 * @param string $mimeType
 * @return string
 */
private function getMediaType($mimeType)
{
    // Si le mimeType est de type image, on retourne "photo"
    if (strpos($mimeType, 'image') !== false) {
        return 'photo';
    }

    // Si le mimeType est de type vidéo, on retourne "video"
    if (strpos($mimeType, 'video') !== false) {
        return 'video';
    }

    // Sinon, on retourne une valeur par défaut (ici 'photo', mais on peut gérer les autres cas)
    return 'photo';
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
       $pets = Pet::with('user')
       ->where('type', 'like', "%$type_name_gender%")
        ->orWhere('name', 'like', "%$type_name_gender%")
        ->orWhere('gender', 'like', "%$type_name_gender%")
         ->orWhereHas('user', function ($query) use ($type_name_gender) {
            $query->where('first_name', 'like', "%$type_name_gender%")
                  ->orWhere('last_name', 'like', "%$type_name_gender%");
        })

        ->get();
        if ($pets->isEmpty()) {
            return response()->json(['message' => 'Aucun animal trouvé'], 404);
        }
        return  response()->json([
            'Pets' => PetResource::collection($pets),
        ]);
    }
}
