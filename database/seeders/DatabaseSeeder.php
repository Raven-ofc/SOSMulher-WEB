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
            // Usuários e administração
            AdminSeeder::class,
            TelefoneAdminSeeder::class,

            // Autoridades e delegacias
            AutoridadeSeeder::class,
            TelefoneAutoridadeSeeder::class,
            DelegaciaSeeder::class,
            TelefoneDelegaciaSeeder::class,

            // Vítimas, guardiões e vínculos
            VitimaSeeder::class,
            TelefoneVitimaSeeder::class,
            EnderecoVitimaSeeder::class,
            GuardiaoSeeder::class,
            TelefoneGuardiaoSeeder::class,
            VitimaGuardiaoSeeder::class,
            LocalizacaoVitimaSeeder::class,

            // Monitoramento eletrônico
            TornozeleiraSeeder::class,
            AgressorSeeder::class,
            LocalizacaoTornozeleiraSeeder::class,

            // Medidas protetivas, violações e alertas
            MedidaSeeder::class,
            ViolacaoSeeder::class,
            AlertaSeeder::class,

            // Ocorrências, relatórios e auditoria
            OcorrenciaSeeder::class,
        ]);
    }
}
