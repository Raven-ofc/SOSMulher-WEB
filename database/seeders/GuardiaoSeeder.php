<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuardiaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbguardiao')->insert([
            [
                'id' => 1,
                'imagemGuardiao' => 'imagens/guardioes/rosangela-penha-maia.jpg',
                'nomeGuardiao' => 'Rosângela Penha Maia',
                'cpfGuardiao' => '118.384.251-10',
                'emailGuardiao' => 'rosangela.penha@gmail.com',
                'senhaGuardiao' => Hash::make('Rosangela@2026'),
                'dataNascimentoGuardiao' => '1971-05-14',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'imagemGuardiao' => 'imagens/guardioes/janis-coelho-farias.jpg',
                'nomeGuardiao' => 'Janis Coelho Farias',
                'cpfGuardiao' => '354.278.498-23',
                'emailGuardiao' => 'janis.cfarias@gmail.com',
                'senhaGuardiao' => Hash::make('Janis@2026'),
                'dataNascimentoGuardiao' => '1980-08-26',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'imagemGuardiao' => 'imagens/guardioes/sonia-ferreira-lima.jpg',
                'nomeGuardiao' => 'Sônia Ferreira Lima',
                'cpfGuardiao' => '084.124.118-03',
                'emailGuardiao' => 'sonia.flima@gmail.com',
                'senhaGuardiao' => Hash::make('Sonia@2026'),
                'dataNascimentoGuardiao' => '1965-02-09',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'imagemGuardiao' => 'imagens/guardioes/rafael-nogueira-campos.jpg',
                'nomeGuardiao' => 'Rafael Nogueira Campos',
                'cpfGuardiao' => '244.935.348-85',
                'emailGuardiao' => 'rafa.ncampos@outlook.com',
                'senhaGuardiao' => Hash::make('Rafael@2026'),
                'dataNascimentoGuardiao' => '1993-10-17',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'imagemGuardiao' => 'imagens/guardioes/ivone-dos-santos-prado.jpg',
                'nomeGuardiao' => 'Ivone dos Santos Prado',
                'cpfGuardiao' => '740.164.005-89',
                'emailGuardiao' => 'ivone.sprado@gmail.com',
                'senhaGuardiao' => Hash::make('Ivone@2026'),
                'dataNascimentoGuardiao' => '1958-12-01',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'imagemGuardiao' => 'imagens/guardioes/marcia-moraes-pinheiro.jpg',
                'nomeGuardiao' => 'Márcia Moraes Pinheiro',
                'cpfGuardiao' => '242.786.801-98',
                'emailGuardiao' => 'marcia.mpinheiro@gmail.com',
                'senhaGuardiao' => Hash::make('Marcia@2026'),
                'dataNascimentoGuardiao' => '1984-07-05',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'imagemGuardiao' => 'imagens/guardioes/jose-barbosa-da-silva.jpg',
                'nomeGuardiao' => 'José Barbosa da Silva',
                'cpfGuardiao' => '128.059.826-30',
                'emailGuardiao' => 'jose.bsilva1962@yahoo.com.br',
                'senhaGuardiao' => Hash::make('Jose@2026'),
                'dataNascimentoGuardiao' => '1962-03-23',
                'statusGuardiao' => 'inativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'imagemGuardiao' => 'imagens/guardioes/luana-tavares-rocha.jpg',
                'nomeGuardiao' => 'Luana Tavares Rocha',
                'cpfGuardiao' => '204.505.331-82',
                'emailGuardiao' => 'luana.trocha@gmail.com',
                'senhaGuardiao' => Hash::make('Luana@2026'),
                'dataNascimentoGuardiao' => '1997-01-11',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'imagemGuardiao' => 'imagens/guardioes/vera-lucia-souza-martins.jpg',
                'nomeGuardiao' => 'Vera Lúcia Souza Martins',
                'cpfGuardiao' => '586.923.226-01',
                'emailGuardiao' => 'vera.smartins@gmail.com',
                'senhaGuardiao' => Hash::make('Vera@2026'),
                'dataNascimentoGuardiao' => '1968-09-30',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'imagemGuardiao' => 'imagens/guardioes/carlos-eduardo-antunes.jpg',
                'nomeGuardiao' => 'Carlos Eduardo Antunes',
                'cpfGuardiao' => '025.634.216-40',
                'emailGuardiao' => 'cadu.antunes@outlook.com',
                'senhaGuardiao' => Hash::make('Carlos@2026'),
                'dataNascimentoGuardiao' => '1982-06-16',
                'statusGuardiao' => 'ativo',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
