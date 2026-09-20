<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalizacaoTornozeleiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Últimos pontos de rastreamento enviados por cada tornozeleira.
     */
    public function run(): void
    {
        DB::table('tblocalizacaotornozeleira')->insert([
            [
                'idTornozeleira' => 1,
                'latitudeLocalizacao' => -23.5543680,
                'longitudeLocalizacao' => -46.4092170,
                'dataHoraLocalizacao' => '2026-09-19 07:15:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 1,
                'latitudeLocalizacao' => -23.5504870,
                'longitudeLocalizacao' => -46.4017320,
                'dataHoraLocalizacao' => '2026-09-11 21:34:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 2,
                'latitudeLocalizacao' => -23.5537920,
                'longitudeLocalizacao' => -46.6620310,
                'dataHoraLocalizacao' => '2026-09-19 08:02:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 3,
                'latitudeLocalizacao' => -23.5840360,
                'longitudeLocalizacao' => -46.4041780,
                'dataHoraLocalizacao' => '2026-09-08 04:52:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 4,
                'latitudeLocalizacao' => -23.5612440,
                'longitudeLocalizacao' => -46.5718930,
                'dataHoraLocalizacao' => '2026-09-19 09:25:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 5,
                'latitudeLocalizacao' => -23.6712850,
                'longitudeLocalizacao' => -46.7521660,
                'dataHoraLocalizacao' => '2026-07-15 17:44:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 6,
                'latitudeLocalizacao' => -23.4982170,
                'longitudeLocalizacao' => -46.6910450,
                'dataHoraLocalizacao' => '2026-09-19 10:08:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 7,
                'latitudeLocalizacao' => -23.6008550,
                'longitudeLocalizacao' => -46.5171930,
                'dataHoraLocalizacao' => '2026-09-17 02:26:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 8,
                'latitudeLocalizacao' => -23.6530740,
                'longitudeLocalizacao' => -46.7641280,
                'dataHoraLocalizacao' => '2026-09-19 06:05:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 9,
                'latitudeLocalizacao' => -23.4903310,
                'longitudeLocalizacao' => -46.7264590,
                'dataHoraLocalizacao' => '2026-08-19 09:31:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idTornozeleira' => 10,
                'latitudeLocalizacao' => -23.6961830,
                'longitudeLocalizacao' => -46.7602440,
                'dataHoraLocalizacao' => '2026-09-19 11:42:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
