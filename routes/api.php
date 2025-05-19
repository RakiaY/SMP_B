<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetController;



Route::get('/test', function() {
    return response()->json(['status' => 'api ymchi cv']);
});

use Illuminate\Support\Facades\Auth;

Route::middleware('auth:sanctum')->get('/test-auth', function () {
    return response()->json(['user' => Auth::user()]);
});


// Routes publiques
Route::post('/pets/add', [PetController::class, 'addPet']);
Route::post('/login', action: [AuthController::class, 'Userlogin']);
Route::post('/registerpetowner', [AuthController::class, 'registerPetOwner']);
Route::post('/registerpetsitter', [AuthController::class, 'registerPetSitter']);



// Routes protégées communes (update profiles, etc)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/pets/add', [PetController::class, 'addPet']);
    // Routes backoffice (super_admin + admin)
    Route::middleware(['role:super_admin|admin'])
        ->prefix('backoffice')
        ->group(base_path('routes/backoffice.php'));

    // Routes mobile (pet-owner + pet-sitter)
    Route::middleware(['role:petowner|petsitter'])
        ->prefix('mobile')
        ->group(base_path('routes/mobile.php'));

});
