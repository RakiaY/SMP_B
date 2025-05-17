<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
    }
}
