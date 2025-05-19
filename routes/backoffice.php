<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\PetOwnerController;
use App\Http\Controllers\PetSitterController;
use App\Http\Controllers\SearchSitterController;
use App\Http\Controllers\PostulationController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AuthController;


// Routes backoffice (super_admin + admin)
Route::middleware(['auth:sanctum','role:super_admin'])->group(function () {
    Route::post('/adminlogin', action: [AuthController::class, 'Adminlogin']);
});

// Gestion des admins (accessible uniquement au super_admin)
Route::middleware(['auth:sanctum','role:super_admin'])->group(function () {
    Route::get('/admins', [SuperAdminController::class, 'getAdmins']);
    Route::get('/admins/{admin_id}', [SuperAdminController::class, 'getAdminById']);
    Route::post('/admins/add', [SuperAdminController::class, 'addAdmin']);
    Route::put('/admins/update/{admin_id}', [SuperAdminController::class, 'updateAdmin']);
    Route::put('/admins/updateStatus/{admin_id}', [SuperAdminController::class, 'updateStatusAdmin']);
    Route::delete('/admins/delete/{admin_id}', [SuperAdminController::class, 'deleteAdmin']);
    Route::post('/admins/restore/{admin_id}', [SuperAdminController::class, 'restoreAdmin']);
    Route::delete('/admins/forceDelete/{admin_id}', [SuperAdminController::class, 'forceDeleteAdmin']);
    Route::get('/admins/getByEmailOrPhone/{email_or_phone}', [SuperAdminController::class, 'getAdminByEmailOrPhone']);
    Route::get('/admins/getByStatut/{status}', [SuperAdminController::class, 'getAdminByStatut']);
});

// Gestion des pet-owners par super_admin & admins
Route::middleware(['auth:sanctum','role:super_admin|admin'])->group(function () {
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
});

// Gestion des pet-sitters par super_admin & admins
Route::middleware(['auth:sanctum','role:super_admin|admin'])->group(function () {
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


// Gestion des recherches & postulations (backoffice)
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
    Route::delete('/postulations/delete/{id}', action: [PostulationController::class, 'deletePostulation']);
        Route::get('/postulations/filterStatut/{statut}', action: [PostulationController::class, 'getPostulationsByStatut']);
        Route::get('/postulations/sitters/get', action: [PostulationController::class, 'getPetSittersForPostulation']);
                Route::get('/postulations/search/{sitter}', action: [PostulationController::class, 'searchBySitter']);


            // Gestion des animaux (backoffice)
            Route::get('/pets', [PetController::class, 'getPets']);
            Route::get('/pets/{id}', [PetController::class, 'getPetById']);
            Route::post('/pets/add', [PetController::class, 'addPet']);
            Route::put('/pets/update/{id}', [PetController::class, 'updatePet']);
            Route::delete('/pets/delete/{id}', [PetController::class, 'deletePet']);
            Route::get('/pets/owner/{id}', [PetController::class, 'getPetsByOwner']);
            Route::get('/pets/search/{type_name_gender}', [PetController::class, 'searchByTypeNameGender']);
            });
