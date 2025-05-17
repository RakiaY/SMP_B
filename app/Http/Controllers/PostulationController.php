<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SearchSitter;
use App\Models\Postulation;
use App\Http\Requests\addPostulationRequest;
use App\Http\Resources\PostulationResource;
use App\Http\Resources\SearchResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\PetSitterResource;

class PostulationController extends Controller
{



    public function getPostulations()
    {
        $postulations = Postulation::with('sitter', 'search' )->get();
        return response()->json([
            'Postulations' => PostulationResource::collection($postulations),
        ]);
    }

    public function addPostulation(addPostulationRequest $request)
{
    $data = $request->validated();
    $data['statut'] = 'en_attente'; 

    $Postulations = [];

    foreach ($data['pet_sitter_ids'] as $sitterId) {

        // 🔎 Vérifier si une postulation existe déjà
        $exists = Postulation::where('search_id', $data['search_id'])
            ->where('sitter_id', $sitterId)
            ->exists();

        if ($exists) {
            // Option 1 : Ignorer les doublons
            continue;

            // Option 2 : Retourner une erreur
            // return response()->json([
            //     'message' => "Le pet-sitter ID $sitterId a déjà postulé à cette recherche."
            // ], 409);
        }

        // ✅ Créer la postulation
        $postulation = Postulation::create([
            'search_id' => $data['search_id'],
            'sitter_id' => $sitterId,
            'statut' => $data['statut'],
        ]);

        $Postulations[] = $postulation;
    }

    return response()->json([
        'Postulations' => PostulationResource::collection($Postulations)
    ]);
}
 public function updatePostulationStatut(Request $request, $id){
    $data = $request->validate([
        'statut' => 'required|string|in:en_attente,acceptée, validée, en cours, terminée,annulée',
    ]);
        $postulation = Postulation::findOrFail($id);

        $postulation->statut = $request->statut;
        $postulation->save();

    return response()->json([
        'postulation' => new PostulationResource($postulation),
    ]);
 }
public function searchBySitterOrOwner($ownerorsitter){
        $postulations = Postulation::with('sitter')
        ->whereHas('sitter', function ($query) use ($ownerorsitter) {
            $query->where('first_name', 'like', "%$ownerorsitter%")
                ->orWhere('last_name', 'like', "%$ownerorsitter%");

        })->get(); 

        return response()->json([
            'Postulations' => PostulationResource::collection($postulations),
        ]);
    }
    public function getPostulationsByStatut($statut){

        $postulations=Postulation::where ('statut', $statut)->get();
        if ($postulations->isEmpty()) {
            return response()->json(['message' => 'Aucunn postulation avec ce statut'], 404);
        }
    
            return response()->json([
            'Postulations' => PostulationResource::collection($postulations),
        ]);
    }
      public function getPetSittersForPostulation() {
        $petSitters = User::role('petsitter')->where('status', ['Active'])->get();
        
        return response()->json([
            'success' => true,
            'petSitters' => PetSitterResource::collection($petSitters)
        ]);
    }


}




