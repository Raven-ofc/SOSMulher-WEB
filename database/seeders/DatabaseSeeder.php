<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
  
            AdminSeeder::class,
            TelefoneAdminSeeder::class,

            AutoridadeSeeder::class,
            TelefoneAutoridadeSeeder::class,
            DelegaciaSeeder::class,
            TelefoneDelegaciaSeeder::class,

            VitimaSeeder::class,
            TelefoneVitimaSeeder::class,
            EnderecoVitimaSeeder::class,
            GuardiaoSeeder::class,
            TelefoneGuardiaoSeeder::class,
            VitimaGuardiaoSeeder::class,
            LocalizacaoVitimaSeeder::class,

            TornozeleiraSeeder::class,
            AgressorSeeder::class,
            LocalizacaoTornozeleiraSeeder::class,

            MedidaSeeder::class,
            ViolacaoSeeder::class,
            AlertaSeeder::class,

            OcorrenciaSeeder::class,
            RelatorioAtendimentoSeeder::class, 
        ]);
    }
}