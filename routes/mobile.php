<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetOwnerController;
use App\Http\Controllers\PetSitterController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SearchSitterController;
use App\Http\Controllers\PostulationController;


// Routes publiques (login, inscription)
//Route::post('/login', [\App\Http\Controllers\AuthController::class, 'Userlogin'])->name('login');
//Route::post('/registerpetowner', [\App\Http\Controllers\AuthController::class, 'registerPetOwner']);
//Route::post('/registerpetsitter', [\App\Http\Controllers\AuthController::class, 'registerPetSitter']);

// Gestion profils Pet Owner & Pet Sitter (mobile)
Route::put('/petowner/update/{id}', [PetOwnerController::class, 'updatePetOwner']);
Route::put('/petsitter/update/{id}', [PetSitterController::class, 'updatePetSitter']);

// Gestion des animaux (mobile)
Route::get('/pets', [PetController::class, 'getPets']);
Route::get('/pets/{id}', [PetController::class, 'getPetById']);
Route::post('/pets/add', [PetController::class, 'addPet']);
Route::put('/pets/update/{id}', [PetController::class, 'updatePet']);
Route::delete('/pets/delete/{id}', [PetController::class, 'deletePet']);
Route::get('/pets/owner/{id}', [PetController::class, 'getPetsByOwner']);
Route::get('/pets/search/{type_name_gender}', [PetController::class, 'searchByTypeNameGender']);

// Gestion des recherches et postulations (mobile)
Route::post('/SearchSitter/add', [SearchSitterController::class, 'addSerach']);
Route::get('/SearchSitter', [SearchSitterController::class, 'getSearchs']);
Route::put('/SearchSitter/update/{id}', [SearchSitterController::class, 'updateSearch']);
Route::delete('/SearchSitter/delete/{id}', [SearchSitterController::class, 'deleteSearch']);
Route::get('/SearchSitter/{id}', [SearchSitterController::class, 'getSearchById']);
Route::get('/SearchSitter/search/{nameOrgardeType}', [SearchSitterController::class, 'getByOwnerName_StartDate']);

Route::post('/postulations/add', [PostulationController::class, 'addPostulation']);
Route::get('/postulations', [PostulationController::class, 'getPostulations']);
Route::post('/postulations/addMultiple', [PostulationController::class, 'addPostulation']);
Route::put('/postulations/updateStatut/{id}', [PostulationController::class, 'updateStatut']);
Route::get('/postulations/{id}', [PostulationController::class, 'getPostulationById']);