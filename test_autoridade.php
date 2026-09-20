y<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$user = \App\Models\tbautoridade::where('emailAutoridade', 'teste@autoridade.com')->first();

echo "User found: " . ($user ? "YES" : "NO") . "\n";
if ($user) {
    echo "Email: " . $user->emailAutoridade . "\n";
    echo "Password hash: " . $user->senhaAutoridade . "\n";
    
    echo "\nTesting password: 'senha123'\n";
    if (Hash::check('senha123', $user->senhaAutoridade)) {
        echo "SENHA CORRETA!\n";
    } else {
        echo "SENHA INCORRETA\n";
    }
    
    $credentials = ['emailAutoridade' => 'teste@autoridade.com', 'password' => 'senha123'];
    echo "\nTentando login: ";
    if (Auth::attempt($credentials)) {
        echo "SUCESSO!\n";
        echo "User ID: " . Auth::id() . "\n";
        echo "User Name: " . Auth::user()->nomeAutoridade . "\n";
    } else {
        echo "FALHOU\n";
    }
}