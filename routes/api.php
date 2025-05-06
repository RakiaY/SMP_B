<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\PetOwnerController;
use App\Http\Controllers\PetSitterController;
use App\Http\Controllers\PetController;

Route::get('/test', function() {
    return response()->json(['status' => 'api ymchi cv']);
});

Route::post('/login', [AuthController::class, 'login']);




Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware([SuperAdminMiddleware::class])->group(function () {

        Route::get('/admins', [SuperAdminController::class, 'getAdmins']);
        Route::get('/admins/{admin_id}', [SuperAdminController::class, 'getAdminById']);
        Route::post('/admins/add', [SuperAdminController::class, 'addAdmin']);
        Route::put('/admins/update/{admin_id}', [SuperAdminController::class, 'updateAdmin']);
        Route::put('/admins/updateStatus/{admin_id}', [SuperAdminController::class, 'updateStatusAdmin']);
        Route::delete('/admins/delete/{admin_id}', [SuperAdminController::class, 'deleteAdmin']);
        Route::post('/admins/restore/{admin_id}', [SuperAdminController::class, 'restoreTrashedAdmin']);
        Route::delete('/admins/forceDelete/{admin_id}', [SuperAdminController::class, 'forceDeleteAdmin']);
        Route::get('/admins/getByEmailOrPhone/{email_or_phone}', [SuperAdminController::class, 'getAdminByEmailOrPhone']);
        Route::get('/admins/getByStatut/{status}', [SuperAdminController::class, 'getAdminByStatut']);
       

    });
});


Route::post('/petowners/add', [PetOwnerController::class, 'addPetOwner']);
Route::get('/petowners', [PetOwnerController::class, 'getPetOwners']);
Route::get('/petowners/{id}', [PetOwnerController::class, 'getPetOwnerById']);
Route::put('/petowners/update/{id}', [PetOwnerController::class, 'updatePetOwner']);
Route::put('/petowners/updateStatut/{id}', [PetOwnerController::class, 'updatePetOwnerStatut']);
Route::delete('/petowners/delete/{id}', [PetOwnerController::class, 'deletePetOwner']);
Route::delete('/petowners/forceDelete/{id}', [PetOwnerController::class, 'forceDeletePetOwner']);
Route::post('/petowners/restore/{id}', [PetOwnerController::class, 'restorePetOwner']);
Route::get('/petowners/getByEmailOrName/{email_or_name}', [PetOwnerController::class, 'getOwnerByEmailOrName']);
Route::get('/petowners/getByStatut/{status}', [PetOwnerController::class, 'getOwnerByStatut']);




Route::post('/petsitters/add', [PetSitterController::class, 'addPetSitter']);
Route::get('/petsitters', [PetSitterController::class, 'getPetSitters']);
Route::get('/petsitters/{id}', [PetSitterController::class, 'getPetSitterById']);
Route::put('/petsitters/update/{id}', [PetSitterController::class, 'updatePetSitter']);
Route::put('/petsitters/updateStatut/{id}', [PetSitterController::class, 'updatePetSitterStatut']);
Route::delete('/petsitters/delete/{id}', [PetSitterController::class, 'deletePetSitter']);
Route::delete('/petsitters/forceDelete/{id}', [PetSitterController::class, 'forceDeletePetSitter']);
Route::post('/petsitters/restore/{id}', [PetSitterController::class, 'restorePetSitter']);
Route::get('/petsitters/getByEmailPhoneOrName/{emailphonename}', [PetSitterController::class, 'getByEmailPhoneOrName']);
Route::get('/petsitters/getByStatut/{status}', [PetSitterController::class, 'getSitterByStatut']);
Route::post('/petsitters/addAdress/{id}', [PetSitterController::class, 'addAdress']);


Route::get('/pets', action: [PetController::class, 'getPets']);
Route::get('/pets/{id}', action: [PetController::class, 'getPetById']);
Route::post(uri: '/pets/add/{id}', action: [PetController::class, 'addPet']);
Route::put(uri: '/pets/update/{id}', action: [PetController::class, 'updatePet']);