<?php

namespace App\Http\Controllers;

use App\Models\SearchSitter;
use App\Models\SearchSitterSlot;
use App\Http\Resources\SearchResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class SearchSitterController extends Controller
{
    /**
     * Create a new pet-sitter search.
     */
    public function addSearch(Request $request)
    {
        $data = $request->validate([
            'user_id'            => 'required|exists:users,id',
            'pet_id'             => 'required|exists:pets,id',
            'adresse'            => 'required|string|max:255',
            'latitude'           => 'required|numeric',
            'longitude'          => 'required|numeric',
            'care_type'          => 'required|string|in:chez_proprietaire,en_chenil',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
            'expected_services'  => 'nullable|string|max:255',
            'remunerationMin'    => 'required|numeric|min:0',
            'remunerationMax'    => 'nullable|numeric|min:0|gte:remunerationMin',
            'passages_per_day'   => 'required_if:care_type,chez_proprietaire|integer|min:1',
            'slots'              => 'required_if:care_type,chez_proprietaire|array|min:1',
            'slots.*.start_time' => 'required_if:care_type,chez_proprietaire|date',
            'slots.*.end_time'   => 'required_if:care_type,chez_proprietaire|date|after:slots.*.start_time',
        ]);

        if ($data['care_type'] === 'chez_proprietaire') {
            foreach ($data['slots'] as $i => $slot) {
                $data['slots'][$i]['start_time'] = Carbon::parse($slot['start_time'])->format('H:i');
                $data['slots'][$i]['end_time']   = Carbon::parse($slot['end_time'])->format('H:i');
            }
        }

        $search = SearchSitter::create(Arr::only($data, [
            'user_id','pet_id','adresse','latitude','longitude',
            'care_type','start_date','end_date',
            'expected_services','remunerationMin','remunerationMax',
            'passages_per_day',
        ]));

        if ($data['care_type'] === 'chez_proprietaire') {
            foreach ($data['slots'] as $i => $slot) {
                SearchSitterSlot::create([
                    'search_pet_sitter_id' => $search->id,
                    'slot_order'           => $i + 1,
                    'start_time'           => $slot['start_time'],
                    'end_time'             => $slot['end_time'],
                ]);
            }
        }

        $search->load(['user','pet','slots','PetMedia']);

        return response()->json([
            'searchSitter' => new SearchResource($search),
        ]);
    }

    /**
     * Get all searches.
     */
    public function getSearchs()
    {
        $searchs = SearchSitter::with(['user','pet','PetMedia'])->get();
        return response()->json([
            'Searchs' => SearchResource::collection($searchs),
        ]);
    }

    /**
     * Update an existing search.
     */
    public function updateSearch(Request $request, $id)
    {
        $search = SearchSitter::findOrFail($id);

        $data = $request->validate([
            'adresse'             => 'sometimes|string|max:255',
            'latitude'            => 'sometimes|numeric',
            'longitude'           => 'sometimes|numeric',
            'care_type'           => 'sometimes|string|in:chez_proprietaire,en_chenil',
            'start_date'          => 'sometimes|date',
            'end_date'            => 'sometimes|date|after_or_equal:start_date',
            'expected_services'   => 'nullable|string|max:255',
            'remunerationMin'     => 'nullable|numeric|min:0',
            'remunerationMax'     => 'nullable|numeric|min:0|gte:remunerationMin',
            'passages_per_day'    => 'sometimes|integer|min:1',
            'slots'               => 'sometimes|array',
            'slots.*.start_time'  => 'sometimes|date_format:H:i',
            'slots.*.end_time'    => 'sometimes|date_format:H:i|after:slots.*.start_time',
        ]);

        $search->update(Arr::only($data, [
            'adresse','latitude','longitude',
            'care_type','start_date','end_date',
            'expected_services','remunerationMin',
            'remunerationMax','passages_per_day',
        ]));

        if (isset($data['slots'])) {
            $search->slots()->delete();
            foreach ($data['slots'] as $i => $slot) {
                SearchSitterSlot::create([
                    'search_pet_sitter_id' => $search->id,
                    'slot_order'           => $i + 1,
                    'start_time'           => Carbon::parse($slot['start_time'])->format('H:i'),
                    'end_time'             => Carbon::parse($slot['end_time'])->format('H:i'),
                ]);
            }
        }

        $search->load(['user','pet','slots','PetMedia']);

        return response()->json([
            'searchSitter' => new SearchResource($search),
        ]);
    }

    /**
     * Delete a search.
     */
    public function deleteSearch($id)
    {
        $search = SearchSitter::findOrFail($id);
        $search->delete();
        return response()->json([
            'message' => 'Search deleted successfully',
        ]);
    }

    /**
     * Get a single search by ID.
     */
    public function getSearchById($id)
    {
        $search = SearchSitter::with(['user','pet','PetMedia','slots'])->findOrFail($id);
        return response()->json([
            'search'     => new SearchResource($search),
            'created_at' => $search->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $search->updated_at->format('d/m/Y H:i:s'),
        ]);
    }

    /**
     * Search by owner name or care type.
     */
    public function getByOwnerName_StartDate($term)
    {
        $searchs = SearchSitter::with(['user'])
            ->whereHas('user', fn($q) =>
                $q->where('first_name','like',"%{$term}%")
                  ->orWhere('last_name','like',"%{$term}%")
            )
            ->orWhere('care_type','like',"%{$term}%")
            ->get();

        return response()->json([
            'searchs' => SearchResource::collection($searchs),
        ]);
    }
}
