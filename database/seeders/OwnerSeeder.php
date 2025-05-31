<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lastNames = ['Kacem', 'Belhassen', 'Bensalem', 'Dahmani', 'Triki', 'Salah', 'Hajji', 'Guesmi', 'Jemai', 'Zribi'];


        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'first_name' => 'owner' . $i,
                'last_name' => $lastNames[$i - 1],
                'email' => "owner{$i}@example.com",
                'password' => 'Owner123',
                'status' => 'Active',
            ]);

            $user->assignRole('petowner');
        }
    }
}
