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