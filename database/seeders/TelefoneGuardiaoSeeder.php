<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefoneGuardiaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbtelefoneguardiao')->insert([
            [
                'numeroTelefoneGuardiao' => '(11) 98231-4407',
                'idGuardiao' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 99715-2260',
                'idGuardiao' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 97604-8813',
                'idGuardiao' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 98357-1192',
                'idGuardiao' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 96420-7735',
                'idGuardiao' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 99188-3306',
                'idGuardiao' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 3742-5519',
                'idGuardiao' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 98066-4471',
                'idGuardiao' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 97529-0084',
                'idGuardiao' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numeroTelefoneGuardiao' => '(11) 98940-6628',
                'idGuardiao' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
