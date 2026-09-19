<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'User',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => hash::make('user1234567'),
            'remember_token' => \Illuminate\Support\Str::random(10),
            'cpf' => '12345678900',
            'phone' => '11987654321',
            'photo_path' => 'default.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]); 
    }
}
