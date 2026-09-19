<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TelefoneVitimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbtelefonevitima')->insert([
            [
                'numeroTelefoneVitima'=> '(11) 91134-2006',
                'idVitima'=> 1,
                'created_at'=>date('Y-m-d H:i:s'), 
                'updated_at'=> date('Y-m-d H:i:s')
            ],
        ]);
                DB::table('tbtelefonevitima')->insert([
            [
                'numeroTelefoneVitima'=> '(11) 98345-2341',
                'idVitima'=> 2,
                'created_at'=>date('Y-m-d H:i:s'), 
                'updated_at'=> date('Y-m-d H:i:s')
            ],
        ]);
    }
}