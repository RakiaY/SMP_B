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
        'care_type', // 'chez_proprietaire' or 'en_chenil'
        'start_date',
        'end_date',
        'expected_services', //( marche;nourrissag; toilettage)
        'remunerationMin',
        'remunerationMax',
        'passages_per_day'
        // Champs spécifiques pour "chez_proprietaire"
        // 'slots' sera géré par la relation hasMany
        // 'slots' => 'required_if:care_type,chez_proprietaire|array',
        // 'slots.*.start_time' => 'required_if:care_type,chez_proprietaire|date',
        // 'slots.*.end_time' => 'required_if:care_type,chez_proprietaire|date|after:slots.*.start_time',
        // 'slots.*.slot_order' => 'required_if:care_type,chez_proprietaire|integer',
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
    public function slots()
    {
        return $this->hasMany(
            SearchSitterSlot::class,
            'search_pet_sitter_id',  // clé étrangère dans la table slots
            'id'                     // clé locale dans search_sitters
        );
    }
    public function PetMedia()
    {
        return $this->hasManyThrough(
            PetMedia::class,
            Pet::class,
            'id', // clé étrangère dans la table pets
            'pet_id', // clé étrangère dans la table pet_media
            'pet_id', // clé locale dans search_sitters
            'id' // clé locale dans pets
        );
    }

}
