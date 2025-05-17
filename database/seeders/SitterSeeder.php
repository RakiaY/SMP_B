<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class SitterSeeder extends Seeder
{
    public function run()
    {
        $lastNames = ['Farhat', 'Najjar', 'Bouazizi', 'Riahi', 'Khlifi', 'Mansour', 'Zouari', 'Toumi', 'Hmidet', 'Karray'];
        $genders = ['Male', 'Female'];


        for ($i = 2; $i <= 10; $i++) {
            $gender = $genders[$i % 2];

            $birthDate = Carbon::now()->subYears(rand(25, 40))->subDays(rand(0, 365))->format('Y-m-d');

            $user = User::create([
                'first_name' => 'sitter' . $i,
                'last_name' => $lastNames[$i - 1],
                'email' => "sitter{$i}@example.com",
                'gender' => $gender,
                'phone' => '22' . rand(100000, 999999),
                'birth_date' => $birthDate,
                'password' => bcrypt('Sitter123'), // 🔒 hashé
            ]);

            $user->assignRole('petsitter');
        }
    }
}
