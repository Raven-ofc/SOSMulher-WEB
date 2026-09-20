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
            [
                'id' => 1,
                'nomeAdmin' => 'Admin',
                'emailAdmin' => 'admin@gmail.com',
                'senhaAdmin' => Hash::make('admin1234567'),
                'cpfAdmin' => '123.456.789-00',
                'dataNascAdmin' => '1990-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nomeAdmin' => 'Renata Coelho Vasconcelos',
                'emailAdmin' => 'renata.vasconcelos@gmail.com',
                'senhaAdmin' => Hash::make('Renata@2026'),
                'cpfAdmin' => '104.332.181-00',
                'dataNascAdmin' => '1985-03-17',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nomeAdmin' => 'Marcelo Andrade Bittencourt',
                'emailAdmin' => 'marcelo.bittencourt@gmail.com',
                'senhaAdmin' => Hash::make('Marcelo@2026'),
                'cpfAdmin' => '960.013.389-14',
                'dataNascAdmin' => '1979-11-02',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nomeAdmin' => 'Patrícia Lemos de Oliveira',
                'emailAdmin' => 'patricia.lemos@gmail.com',
                'senhaAdmin' => Hash::make('Patricia@2026'),
                'cpfAdmin' => '083.863.794-99',
                'dataNascAdmin' => '1992-07-25',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'nomeAdmin' => 'Thiago Moreira Sampaio',
                'emailAdmin' => 'thiago.sampaio@gmail.com',
                'senhaAdmin' => Hash::make('Thiago@2026'),
                'cpfAdmin' => '026.542.351-14',
                'dataNascAdmin' => '1988-01-09',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'nomeAdmin' => 'Juliana Prado Meneghetti',
                'emailAdmin' => 'juliana.meneghetti@gmail.com',
                'senhaAdmin' => Hash::make('Juliana@2026'),
                'cpfAdmin' => '161.559.407-89',
                'dataNascAdmin' => '1994-05-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'nomeAdmin' => 'Fábio Henrique Nakamura',
                'emailAdmin' => 'fabio.nakamura@gmail.com',
                'senhaAdmin' => Hash::make('Fabio@2026'),
                'cpfAdmin' => '816.184.959-50',
                'dataNascAdmin' => '1983-09-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'nomeAdmin' => 'Simone Ferraz Aguiar',
                'emailAdmin' => 'simone.aguiar@gmail.com',
                'senhaAdmin' => Hash::make('Simone@2026'),
                'cpfAdmin' => '310.341.316-56',
                'dataNascAdmin' => '1976-12-04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'nomeAdmin' => 'Rodrigo Quintanilha Serra',
                'emailAdmin' => 'rodrigo.serra@gmail.com',
                'senhaAdmin' => Hash::make('Rodrigo@2026'),
                'cpfAdmin' => '475.255.341-44',
                'dataNascAdmin' => '1990-06-18',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'nomeAdmin' => 'Luciana Barros Tavares',
                'emailAdmin' => 'luciana.tavares@gmail.com',
                'senhaAdmin' => Hash::make('Luciana@2026'),
                'cpfAdmin' => '928.327.648-51',
                'dataNascAdmin' => '1987-02-21',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
