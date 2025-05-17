<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Charger les routes de l'API
        Route::middleware(['api', 'auth:sanctum'])
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        // Charger les routes backoffice
        Route::middleware(['api', 'auth:sanctum', 'role:super_admin|admin'])
            ->prefix('backoffice')
            ->group(base_path('routes/backoffice.php'));

        // Charger les routes mobile
        Route::middleware(['api', 'auth:sanctum', 'role:petowner|petsitter'])
            ->prefix('mobile')
            ->group(base_path('routes/mobile.php'));
    }
}