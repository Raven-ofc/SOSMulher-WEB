<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AutoridadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbautoridade')->insert([
            [
                'id' => 1,
                'nomeAutoridade' => 'Raven',
                'imagemAutoridade' => 'iaciasi.png',
                'emailAutoridade' => 'raven.oficial.tcc@gmail.com',
                'cpfAutoridade' => '123.456.789-00',
                'matriculaAutoridade' => '123456789',
                'cargoAutoridade' => 'Administrador',
                'unidadeAutoridade' => 'Unidade Administrativa',
                'senhaAutoridade' => hash::make('RavenTcc'),
                'statusAutoridade' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nomeAutoridade' => 'Raven_TCC',
                'imagemAutoridade' => 'iaciasi.png',
                'emailAutoridade' => 'raven@gmail.com',
                'cpfAutoridade' => '123.456.789-01',
                'matriculaAutoridade' => '123456781',
                'cargoAutoridade' => 'Administrador',
                'unidadeAutoridade' => 'Unidade Administrativa',
                'senhaAutoridade' => hash::make('RavenTcc_118'),
                'statusAutoridade' => 'Ativo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
     