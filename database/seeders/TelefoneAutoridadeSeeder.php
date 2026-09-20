<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefoneAutoridadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tbtelefoneautoridade')->insert([
            [
                'numTelefoneAutoridade' => '(11) 3311-2020',
                'idAutoridade' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11) 3311-2021',
                'idAutoridade' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)98130-4471',
                'idAutoridade' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)99623-8810',
                'idAutoridade' => 4,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)97205-6634',
                'idAutoridade' => 5,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)98477-1152',
                'idAutoridade' => 6,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)96018-3397',
                'idAutoridade' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)99351-7028',
                'idAutoridade' => 8,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)98744-2265',
                'idAutoridade' => 9,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'numTelefoneAutoridade' => '(11)97663-9014',
                'idAutoridade' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
