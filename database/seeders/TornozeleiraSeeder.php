<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TornozeleiraSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('tbtornozeleira')->insert([
            [
                'id' => 1,
                'numeroSerieTornozeleira' => 'TZ001',
                'statusTornozeleira' => 'Ativa',
                'dataInstalacaoTornozeleira' => '2026-09-06',
                'bateriaTornozeleira' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'numeroSerieTornozeleira' => 'TZ002',
                'statusTornozeleira' => 'Ativa',
                'dataInstalacaoTornozeleira' => '2026-09-10',
                'bateriaTornozeleira' => 85,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'numeroSerieTornozeleira' => 'TZ003',
                'statusTornozeleira' => 'Manutenção',
                'dataInstalacaoTornozeleira' => '2026-08-20',
                'bateriaTornozeleira' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'numeroSerieTornozeleira' => 'TZ004',
                'statusTornozeleira' => 'Ativa',
                'dataInstalacaoTornozeleira' => '2026-09-01',
                'bateriaTornozeleira' => 92,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'numeroSerieTornozeleira' => 'TZ005',
                'statusTornozeleira' => 'Inativa',
                'dataInstalacaoTornozeleira' => '2026-07-15',
                'bateriaTornozeleira' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}