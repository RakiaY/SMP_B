<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SearchSitterController;



// Routes publiques
Route::post('/login', action: [AuthController::class, 'Userlogin']);
Route::post('/adminlogin', action: [AuthController::class, 'Adminlogin']);
Route::post('/registerpetowner', [AuthController::class, 'registerPetOwner']);
Route::post('/registerpetsitter', [AuthController::class, 'registerPetSitter']);


Route::get('/pets/ByOwner/{id}', [PetController::class, 'getPetsByOwner']);
Route::post('/pets/add', [PetController::class, 'addPet']);
Route::delete('/pets/delete/{id}', [PetController::class, 'deletePet']);
Route::put('/pets/update/{id}', [PetController::class, 'updatePet']);
Route::get('/pets/{id}', [PetController::class, 'getPetById']);

Route::post('/SearchSitter/add', [SearchSitterController::class, 'addSearch']);
Route::get('/SearchSitter', [SearchSitterController::class, 'getSearchs']);

// Gestion des recherches et postulations (mobile)
Route::get('/SearchSitter', [SearchSitterController::class, 'getSearchs']);
Route::put('/SearchSitter/update/{id}', [SearchSitterController::class, 'updateSearch']);
Route::delete('/SearchSitter/delete/{id}', [SearchSitterController::class, 'deleteSearch']);
Route::get('/SearchSitter/{id}', [SearchSitterController::class, 'getSearchById']);
Route::get('/SearchSitter/search/{nameOrgardeType}', [SearchSitterController::class, 'getByOwnerName_StartDate']);


// Routes protégées communes (update profiles, etc)
Route::middleware(['auth:sanctum'])->group(function () {
    // Routes backoffice (super_admin + admin)
    Route::middleware(['role:super_admin|admin'])
        ->prefix('backoffice')
        ->group(base_path('routes/backoffice.php'));

    // Routes mobile (pet-owner + pet-sitter)
    Route::middleware(['role:petowner|petsitter'])
        ->prefix('mobile')
        ->group(base_path('routes/mobile.php'));

});
