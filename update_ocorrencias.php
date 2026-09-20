<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\tbocorrencia;

$updated = tbocorrencia::query()->update(['idAutoridade' => 5]);

echo "Atualizadas {$updated} ocorrencias para autoridade ID 5 (seu usuario)\n";

$ocs = tbocorrencia::all();
foreach ($ocs as $oc) {
    echo "OC ID: {$oc->id}, idAutoridade: {$oc->idAutoridade}\n";
}