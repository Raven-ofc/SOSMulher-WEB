<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\tbocorrencia;
use App\Models\tbautoridade;

$ocorrencias = tbocorrencia::all();
echo "Total ocorrencias: " . $ocorrencias->count() . "\n";

foreach ($ocorrencias as $oc) {
    echo "Ocorrencia ID: {$oc->id}, idAutoridade: {$oc->idAutoridade}\n";
}

$autoridades = tbautoridade::all();
echo "\nAutoridades:\n";
foreach ($autoridades as $a) {
    echo "ID: {$a->id}, Email: {$a->emailAutoridade}, Nome: {$a->nomeAutoridade}\n";
}