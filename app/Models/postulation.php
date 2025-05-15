<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulation extends Model
{
    protected $table = 'postulations';
     protected $fillable = [
        'sitter_id',
        'search_id',
        'statut'];

    public function search()
    {
        return $this->belongsTo(SearchSitter::class, 'search_id');
    }
    
    public function sitter()
    {
        return $this->belongsTo(User::class, 'sitter_id');
    }
}
