<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VitimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbvitima')->insert([
            [
                'nomeVitima' => 'Maria da Penha',
                'senhaVitima'=>Hash::make('123456'),
                'cpfVitima' => '113.402.006-00',
                'emailVitima' =>'MariaPn@gmail.com',
                'dataNascimentoVitima' =>'1945-02-01',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/8qVH18DS8DaPY6UZYys6L6pseHz7TLIXmDDrLnk9.jpg',
                'created_at'=>date('Y-m-d H:i:s'), 
                'updated_at'=> date('Y-m-d H:i:s')
            ],
            [
                'nomeVitima' => 'Amy Winehouse',
                'senhaVitima'=>Hash::make('234567'),
                'cpfVitima' => '230.620.110-00',
                'emailVitima' =>'amyHouse@gmail.com',
                'dataNascimentoVitima' =>'1983-09-14',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/S2h9AXol4FSLQnqg7WrEyJuHv0S9FByBzwUapiGF.jpg',
                'created_at'=>date('Y-m-d H:i:s'), 
                'updated_at'=> date('Y-m-d H:i:s')
            ]
        ]);
    }
}
