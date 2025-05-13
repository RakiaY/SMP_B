<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchSitter extends Model
{
    protected $table = 'search_pet_sitters';
    protected $fillable = [
        'user_id',
        'pet_id',
        'adresse',
        'latitude',
        'longitude',
        'description',
        'care_type', // 'chez_proprietaire' or 'en_chenil'
        'care_duration',
        'start_date',
        'end_date',
        'expected_services', //( marche;nourrissag; toilettage)
        'remunerationMin',
        'remunerationMax',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
       
    ];
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
