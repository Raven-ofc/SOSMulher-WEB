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
                'id' => 1,
                'nomeVitima' => 'Maria da Penha',
                'senhaVitima' => Hash::make('123456'),
                'cpfVitima' => '113.402.006-00',
                'emailVitima' => 'MariaPn@gmail.com',
                'dataNascimentoVitima' => '1945-02-01',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/8qVH18DS8DaPY6UZYys6L6pseHz7TLIXmDDrLnk9.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'nomeVitima' => 'Amy Winehouse',
                'senhaVitima' => Hash::make('234567'),
                'cpfVitima' => '230.620.110-00',
                'emailVitima' => 'amyHouse@gmail.com',
                'dataNascimentoVitima' => '1983-09-14',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/S2h9AXol4FSLQnqg7WrEyJuHv0S9FByBzwUapiGF.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'nomeVitima' => 'Adriana Ferreira Lima',
                'senhaVitima' => Hash::make('Adriana@2026'),
                'cpfVitima' => '132.677.360-72',
                'emailVitima' => 'adriana.flima@gmail.com',
                'dataNascimentoVitima' => '1991-04-22',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/adriana-ferreira-lima.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'nomeVitima' => 'Beatriz Nogueira Campos',
                'senhaVitima' => Hash::make('Beatriz@2026'),
                'cpfVitima' => '260.647.468-66',
                'emailVitima' => 'bia.nogueira.campos@outlook.com',
                'dataNascimentoVitima' => '1996-11-08',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/beatriz-nogueira-campos.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'nomeVitima' => 'Cláudia Regina dos Santos',
                'senhaVitima' => Hash::make('Claudia@2026'),
                'cpfVitima' => '723.430.980-26',
                'emailVitima' => 'claudia.rsantos@gmail.com',
                'dataNascimentoVitima' => '1978-06-30',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/claudia-regina-santos.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'nomeVitima' => 'Daniela Moraes Pinheiro',
                'senhaVitima' => Hash::make('Daniela@2026'),
                'cpfVitima' => '500.978.820-97',
                'emailVitima' => 'dani.moraes.pinheiro@gmail.com',
                'dataNascimentoVitima' => '1988-01-19',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/daniela-moraes-pinheiro.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'nomeVitima' => 'Eliane Barbosa da Silva',
                'senhaVitima' => Hash::make('Eliane@2026'),
                'cpfVitima' => '812.191.361-66',
                'emailVitima' => 'eliane.bsilva@yahoo.com.br',
                'dataNascimentoVitima' => '1969-09-03',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/eliane-barbosa-silva.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'nomeVitima' => 'Fernanda Tavares Rocha',
                'senhaVitima' => Hash::make('Fernanda@2026'),
                'cpfVitima' => '939.909.169-47',
                'emailVitima' => 'fernanda.trocha@gmail.com',
                'dataNascimentoVitima' => '1999-03-12',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/fernanda-tavares-rocha.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'nomeVitima' => 'Gabriela Souza Martins',
                'senhaVitima' => Hash::make('Gabriela@2026'),
                'cpfVitima' => '985.435.346-07',
                'emailVitima' => 'gabi.souzamartins@gmail.com',
                'dataNascimentoVitima' => '1993-07-27',
                'statusVitima' => 'inativo',
                'imagemVitima' => 'imagens/vitimas/gabriela-souza-martins.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'nomeVitima' => 'Helena Ribeiro Antunes',
                'senhaVitima' => Hash::make('Helena@2026'),
                'cpfVitima' => '247.510.799-56',
                'emailVitima' => 'helena.rantunes@outlook.com',
                'dataNascimentoVitima' => '1985-12-05',
                'statusVitima' => 'ativo',
                'imagemVitima' => 'imagens/vitimas/helena-ribeiro-antunes.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
