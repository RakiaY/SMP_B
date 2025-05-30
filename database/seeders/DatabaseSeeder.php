<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'first_name' => 'super',
            'last_name' => 'admin',
            'email' => 'superadmin@gmail.com',
            'password' =>'Super123', // Mot de passe DOIT être hashé
            'email_verified_at' => now(),
        ]);

        // 3. Attribuer le rôle (version sécurisée)
            $superAdmin->assignRole('super_admin');
            $this->call([
                 RoleSeeder::class, 
                    AdminSeeder::class,
                    SitterSeeder::class,
                    OwnerSeeder::class,

                ]);
       
       
        

        
    }

}
