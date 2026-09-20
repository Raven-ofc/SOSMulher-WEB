<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$email = 'raven.oficial.tcc@gmail.com';
$password = 'RavenTcc_118';

$user = \App\Models\tbautoridade::where('emailAutoridade', $email)->first();

echo "User found: " . ($user ? "YES" : "NO") . "\n";
if ($user) {
    echo "Nome: " . $user->nomeAutoridade . "\n";
    echo "Email: " . $user->emailAutoridade . "\n";
    echo "ID: " . $user->id . "\n";
    echo "Hash: " . $user->senhaAutoridade . "\n";
    
    if (Hash::check($password, $user->senhaAutoridade)) {
        echo "\nSENHA 'RavenTcc_118' CONFERE!\n";
    } else {
        echo "\nSENHA NÃO CONFERE\n";
        echo "Hash esperado: " . Hash::make($password) . "\n";
    }
    
    $credentials = ['emailAutoridade' => $email, 'password' => $password];
    if (Auth::attempt($credentials)) {
        echo "LOGIN SUCESSO!\n";
        echo "Auth ID: " . Auth::id() . "\n";
        echo "Auth User: " . Auth::user()->nomeAutoridade . "\n";
    } else {
        echo "LOGIN FALHOU\n";
    }
}