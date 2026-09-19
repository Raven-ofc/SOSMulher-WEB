<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnderecoVitimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbenderecovitima')->insert([
            [
                'logradouroVitima' => 'Rua Inácio Moreira',
                'numLogradouroVitima' => '444',
                'bairroVitima' => 'Guaianases',
                'cidadeVitima' => 'São Paulo',
                'ufVitima' => 'SP',
                'complementoVitima'=> null,
                'cepVitima'=> '08460-300',
                'idVitima'=> 1,
                'latitudeVitima'=> -23.550093,
                'longitudeVitima'=> -46.400124,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}