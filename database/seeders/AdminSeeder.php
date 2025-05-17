<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lastNames = ['Ben Salah', 'Gharbi', 'Trabelsi', 'Jaziri', 'Mahjoub', 'Maaloul', 'Haddad', 'Chaabani', 'Sassi', 'Chebbi'];

        // Crée le rôle admin s'il n'existe pas

        for ($i = 1; $i <= 10; $i++) {
            $user =  User::updateOrCreate([
                'first_name' => 'Admin' . $i,
                'last_name' => $lastNames[$i - 1],
                'email' => "admin{$i}@example.com",
                'password' => 'Admin123',
                'phone' => '22' . rand(100000, 999999),
                'status' => 'Active',

            ]);

            // Assigne le rôle admin
            $user->assignRole('admin');
        }
    }

}
