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
class PostulationController extends Controller
{
  public function addPostulation(Request $request)
{
    try {
        $data = $request->validate([
            'sitter_id' => 'required|exists:users,id',
            'search_id' => 'required|exists:search_pet_sitters,id',
            'statut' => 'required|string|in:en_attente,acceptee,refusee',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 400); // ici on force le code 400 au lieu du 422
    }
    // Vérifie si l'utilisateur un pet-sitter
    $sitter = User::find($data['sitter_id']);

if (!$sitter || !$sitter->hasRole('petsitter')) {
    return response()->json(['error' => 'L\'utilisateur n\'est pas un pet-sitter'], 403);
}


    $postulation = Postulation::create($data);

$postulation->load('search.user'); // Charge search et son user

        return response()->json([
            'postulation' => new PostulationResource($postulation),
        ]);
            
    }


    public function getPostulations()
    {
        $postulations = Postulation::with('sitter', 'search')->where('statut', '!=', 'Canceled')->get();
        return response()->json([
            'Postulations' => PostulationResource::collection($postulations),
        ]);
    }

}
