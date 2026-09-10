<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbadmin')->insert([
            'id' => 1,
            'nomeAdmin' => 'Admin',
            'emailAdmin' => 'admin@gmail.com',
            'senhaAdmin' => hash::make('admin1234567'),
            'cpfAdmin' => '123.456.789-00',
            'dataNascAdmin' => '1990-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
