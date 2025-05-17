<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;




Route::get('/test', function() {
    return response()->json(['status' => 'api ymchi cv']);
});

Route::post('backoffice/login', action: [AuthController::class, 'Adminlogin']);
Route::post('mobile/login', action: [AuthController::class, 'Userlogin']);

Route::post('/registerpetowner', [AuthController::class, 'registerPetOwner']);
Route::post('/registerpetsitter', [AuthController::class, 'registerPetSitter']);



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
