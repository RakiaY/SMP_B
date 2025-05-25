<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchSitterSlot extends Model
{
    
    protected $table = 'search_pet_sitter_slots';  
      protected $fillable = [
        'search_pet_sitter_id',
        'slot_order',
        'start_time',
        'end_time',
    ];
    public function SearchSitter()
    {
        return $this->belongsTo(SearchSitter::class);
    }
}
