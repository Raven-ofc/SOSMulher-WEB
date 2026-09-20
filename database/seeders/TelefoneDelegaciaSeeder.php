<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefoneDelegaciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbTelefoneDelegacia')->insert([
            [
                'numeroTelefoneDelegacia' => '(11) 3241-2325',
                'idDelegacia' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 5687-1444',
                'idDelegacia' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 2073-4188',
                'idDelegacia' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 2557-6920',
                'idDelegacia' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 3721-0345',
                'idDelegacia' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 2521-7788',
                'idDelegacia' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 2015-3390',
                'idDelegacia' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 3742-6611',
                'idDelegacia' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 3311-4050',
                'idDelegacia' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneDelegacia' => '(11) 2211-9074',
                'idDelegacia' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
