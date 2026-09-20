<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\tbautoridade;
use App\Models\tbocorrencia;

$autoridades = tbautoridade::all();
echo "Todas as autoridades:\n";
foreach ($autoridades as $a) {
    echo "ID: {$a->id}, Email: {$a->emailAutoridade}, Nome: {$a->nomeAutoridade}\n";
}

echo "\nOcorrencias por autoridade:\n";
foreach ($autoridades as $a) {
    $ocs = tbocorrencia::where('idAutoridade', $a->id)->get();
    echo "Autoridade {$a->id} ({$a->nomeAutoridade}): {$ocs->count()} ocorrencias\n";
    foreach ($ocs as $oc) {
        echo "  - OC ID: {$oc->id}, Tipo: {$oc->tipoOcorrencia}, Vitima: {$oc->idVitima}, Agressor: {$oc->idAgressor}\n";
    }
}