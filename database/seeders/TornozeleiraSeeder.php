<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TornozeleiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbtornozeleira')->insert([
            ['id'=>1,'numeroSerieTornozeleira'=>'TZ001' ,'statusTornozeleira'=>'Ativa', 'dataInstalacaoTornozeleira'=>'2026-09-06', 'bateriaTornozeleira'=>100],
        ]);
    }
}
