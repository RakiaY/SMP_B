<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\SearchSitter;
use App\Models\SearchSitterSlot;
use App\Http\Requests\addSearchSitterRequest;
use App\Http\Resources\PetResource;
use App\Http\Resources\SearchResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class SearchSitterController extends Controller
{
    
public function addSearch(Request $request)
{
    $data = $request->validate([
        'user_id'            => 'required|exists:users,id',
        'pet_id'             => 'required|exists:pets,id',
        'adresse'            => 'required|string|max:255',
        'latitude'           => 'required|numeric',
        'longitude'          => 'required|numeric',
        'description'        => 'nullable|string|max:1000',
        'care_type'          => 'required|string|in:chez_proprietaire,en_chenil',
        'start_date'         => 'required|date',
        'end_date'           => 'required|date|after_or_equal:start_date',
        'expected_services'  => 'nullable|string|max:255',
        'remunerationMin'    => 'required|numeric|min:0',
        'remunerationMax'    => 'nullable|numeric|min:0|gte:remunerationMin',

        // 🌟 Champs spécifiques à "chez_proprietaire"
        'passages_per_day'         => 'required_if:care_type,chez_proprietaire|integer|min:1',
        'slots'                    => 'required_if:care_type,chez_proprietaire|array|min:1',
        'slots'                  => 'required_if:care_type,chez_proprietaire|array',
        'slots.*.start_time'     => 'required_if:care_type,chez_proprietaire|date',
        'slots.*.end_time'       => 'required_if:care_type,chez_proprietaire|date|after:slots.*.start_time',
    ]);

     //  Si on est en visite à domicile, on reformate chaque chaîne en "HH:mm"
    if (($data['care_type'] ?? '') === 'chez_proprietaire') {
        foreach ($data['slots'] as $i => $slot) {
            // Carbon parse accepte ISO ou H:i:s
            $data['slots'][$i]['start_time'] = Carbon::parse($slot['start_time'])->format('H:i');
            $data['slots'][$i]['end_time']   = Carbon::parse($slot['end_time'])->format('H:i');
        }
    }

    // 2️⃣ Création de la recherche principale
    $searchSitter = SearchSitter::create(Arr::only($data, [
        'user_id','pet_id','adresse','latitude','longitude',
        'description','care_type','start_date','end_date',
        'expected_services','remunerationMin','remunerationMax',
        'passages_per_day',
    ]));

    // 3️⃣ Si garde "chez le propriétaire", on crée les créneaux
    if ($data['care_type'] === 'chez_proprietaire') {
        foreach ($data['slots'] as $index => $slot) {
            SearchSitterSlot::create([
                'search_pet_sitter_id' => $searchSitter->id,
                'slot_order'       => $index + 1,
                'start_time' => $slot['start_time'],
                'end_time'   => $slot['end_time'],
            ]);
        }
    }

    // 4️⃣ Charger les relations pour la réponse
    $searchSitter->load(['user', 'pet', 'slots','PetMedia']);

    return response()->json([
        'searchSitter' => new SearchResource($searchSitter),
    ]);
}


   public function getSearchs(){
    $searchs = SearchSitter::with(['user', 'pet','PetMedia'])->get();
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
    









