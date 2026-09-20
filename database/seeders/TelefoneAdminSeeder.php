<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefoneAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbtelefoneadmin')->insert([
            [
                'numTelefoneAdmin' => '(11) 3291-4400',
                'idAdmin' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 98812-7734',
                'idAdmin' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 99160-3218',
                'idAdmin' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 97455-9021',
                'idAdmin' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 96330-1187',
                'idAdmin' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 98207-6642',
                'idAdmin' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 99074-5513',
                'idAdmin' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 97718-2290',
                'idAdmin' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 98564-3376',
                'idAdmin' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAdmin' => '(11) 96841-7709',
                'idAdmin' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
