<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalizacaoVitimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Última posição conhecida de cada vítima, enviada pelo aplicativo móvel.
     */
    public function run(): void
    {
        DB::table('tblocalizacaovitima')->insert([
            [
                'idVitima' => 1,
                'latitudeLocalizacao' => -23.5500930,
                'longitudeLocalizacao' => -46.4001240,
                'dataHoraLocalizacao' => '2026-09-18 20:12:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 1,
                'latitudeLocalizacao' => -23.5487410,
                'longitudeLocalizacao' => -46.4053780,
                'dataHoraLocalizacao' => '2026-09-19 07:48:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 2,
                'latitudeLocalizacao' => -23.5460210,
                'longitudeLocalizacao' => -46.6395470,
                'dataHoraLocalizacao' => '2026-09-19 08:05:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 3,
                'latitudeLocalizacao' => -23.5847130,
                'longitudeLocalizacao' => -46.4026880,
                'dataHoraLocalizacao' => '2026-09-19 06:55:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 4,
                'latitudeLocalizacao' => -23.5541780,
                'longitudeLocalizacao' => -46.5644910,
                'dataHoraLocalizacao' => '2026-09-19 09:22:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 5,
                'latitudeLocalizacao' => -23.6645720,
                'longitudeLocalizacao' => -46.7609310,
                'dataHoraLocalizacao' => '2026-09-19 07:31:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 6,
                'latitudeLocalizacao' => -23.4936480,
                'longitudeLocalizacao' => -46.6837590,
                'dataHoraLocalizacao' => '2026-09-19 10:04:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 7,
                'latitudeLocalizacao' => -23.5996230,
                'longitudeLocalizacao' => -46.5184470,
                'dataHoraLocalizacao' => '2026-09-19 11:17:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 8,
                'latitudeLocalizacao' => -23.6482350,
                'longitudeLocalizacao' => -46.7593410,
                'dataHoraLocalizacao' => '2026-09-19 08:39:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 9,
                'latitudeLocalizacao' => -23.4871560,
                'longitudeLocalizacao' => -46.7305880,
                'dataHoraLocalizacao' => '2026-09-19 07:02:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'idVitima' => 10,
                'latitudeLocalizacao' => -23.6902740,
                'longitudeLocalizacao' => -46.7538190,
                'dataHoraLocalizacao' => '2026-09-19 09:50:00',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
