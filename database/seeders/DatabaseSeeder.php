<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($number = 1; $number <= 40; $number++) { 
            User::factory()->create([ 'name' => 'user'.$number, 'email' => 'user'.$number.'@example.com', 'password' => Hash::make('1'), ]); 
        } 
            
        $this->call([ ProductSeeder::class, ]);
    }
}
