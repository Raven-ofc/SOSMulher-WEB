<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefoneVitimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbtelefonevitima')->insert([
            [
                'numeroTelefoneVitima' => '(11) 91134-2006',
                'idVitima' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 98345-2341',
                'idVitima' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 99427-1183',
                'idVitima' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 97250-6649',
                'idVitima' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 98806-3372',
                'idVitima' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 96118-7704',
                'idVitima' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 99530-2287',
                'idVitima' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 98014-5590',
                'idVitima' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 97382-1046',
                'idVitima' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneVitima' => '(11) 96745-8830',
                'idVitima' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
