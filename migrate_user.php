<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\tbautoridade;
use Illuminate\Support\Facades\Hash;

$originalUser = \App\Models\User::where('email', 'raven.oficial.tcc@gmail.com')->first();

if ($originalUser) {
    // Check if already exists in tbautoridade
    $exists = tbautoridade::where('emailAutoridade', $originalUser->email)->first();
    
    if ($exists) {
        echo "Usuário já existe em tbautoridade\n";
    } else {
        tbautoridade::create([
            'imagemAutoridade' => 'default.jpg',
            'nomeAutoridade' => $originalUser->name,
            'emailAutoridade' => $originalUser->email,
            'cpfAutoridade' => '000.000.000-00', // placeholder
            'matriculaAutoridade' => now(),
            'cargoAutoridade' => 'Administrador',
            'unidadeAutoridade' => 'SOS Mulher',
            'senhaAutoridade' => $originalUser->password, // same hash
            'statusAutoridade' => 'ativo',
            'remember_token' => $originalUser->remember_token,
        ]);
        echo "Usuário migrado para tbautoridade com sucesso!\n";
        echo "Email: " . $originalUser->email . "\n";
        echo "Senha (hash mantido): " . $originalUser->password . "\n";
    }
} else {
    echo "Usuário original não encontrado\n";
}