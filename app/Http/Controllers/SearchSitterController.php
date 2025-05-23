<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\SearchSitter;
use App\Http\Requests\addSearchSitterRequest;
use App\Http\Resources\PetResource;
use App\Http\Resources\SearchResource;
use Illuminate\Http\Request;

class SearchSitterController extends Controller
{
    
    public function addSerach(Request $request)
{
    $data = $request->validate([
        'user_id' => 'required|exists:users,id',
        'pet_id' => 'required|exists:pets,id',
        'adresse' => 'required|string|max:255',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'description' => 'nullable|string|max:1000',
        'care_type' => 'required|string|in:chez_proprietaire,en_chenil',
        'care_duration' => 'nullable|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'expected_services' => 'nullable|string|max:255',
        'remunerationMin' => 'required|numeric|min:0',
        'remunerationMax' => 'nullable|numeric|min:0|gte:remunerationMin',
    ]);

    // Enregistrement
    $searchSitter = SearchSitter::create($data);

    // Charger relations
    $searchSitter->load(['user', 'pet']);

    return response()->json([
        'searchSitter' => new SearchResource($searchSitter),
    ]);
}

   public function getSearchs(){
    $searchs = SearchSitter::with(['user', 'pet'])->get();
    return response()->json([
        'Searchs' => SearchResource::collection($searchs),
    ]);
}

    public function updateSearch(Request $request, $id)
    {
       
        $search = SearchSitter::findOrFail($id);
        $data = $request->validate([
            
            'adresse' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'description' => 'nullable|string|max:1000',
            'care_type' => 'sometimes|string|in:chez_proprietaire,en_chenil',
            'care_duration' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'expected_services' => 'nullable|string|max:255',
            'remunerationMin' => 'nullable|numeric|min:0',
            'remunerationMax' => 'nullable|numeric|min:0|gte:remunerationMin',
        ]);
        $search->update($data);
        return response()->json([
            
            'search' => new SearchResource($search),
        ]);
    }
    public function deleteSearch($id)
    {
        $search = SearchSitter::findOrFail($id);
        $search->delete();
        return response()->json([
            'message' => 'Search deleted successfully',
        ]);
    }
    public function getSearchById($id)
    {
        $search = SearchSitter::with(['user', 'pet'])->findOrFail($id);
        return response()->json([
            'search' => new SearchResource($search),
            'created_at' => $search->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $search->updated_at->format('d/m/Y H:i:s'),
        ]);
    }

   public function getByOwnerName_StartDate($nameOrgardeType)
{
    $search = SearchSitter::with(['user'])
        ->where(function ($query) use ($nameOrgardeType) {
            // Recherche par nom de l'utilisateur (prénom ou nom)
            $query->whereHas('user', function ($query) use ($nameOrgardeType) {
                $query->where('first_name', 'like', "%$nameOrgardeType%")
                      ->orWhere('last_name', 'like', "%$nameOrgardeType%");
            })
            // Recherche par date de début (start_date)
            ->orWhere('care_type', 'like', "%$nameOrgardeType%");
        })
        ->get();

    return response()->json([
        'searchs' => SearchResource::collection($search),
    ]);
}

    
      



}
    









