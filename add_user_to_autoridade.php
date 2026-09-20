<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\tbautoridade;

$u = \App\Models\User::where('email', 'raven.oficial.tcc@gmail.com')->first();

if ($u) {
    $exists = tbautoridade::where('emailAutoridade', $u->email)->first();
    
    if ($exists) {
        echo "Já existe em tbautoridade:\n";
        echo "  ID: {$exists->id}\n";
        echo "  Email: {$exists->emailAutoridade}\n";
    } else {
        tbautoridade::create([
            'imagemAutoridade' => 'default.jpg',
            'nomeAutoridade' => $u->name,
            'emailAutoridade' => $u->email,
            'cpfAutoridade' => '000.000.000-00',
            'matriculaAutoridade' => now(),
            'cargoAutoridade' => 'Administrador',
            'unidadeAutoridade' => 'SOS Mulher',
            'senhaAutoridade' => $u->password,
            'statusAutoridade' => 'ativo',
            'remember_token' => $u->remember_token,
        ]);
        echo "Adicionado a tbautoridade com sucesso!\n";
    }
}