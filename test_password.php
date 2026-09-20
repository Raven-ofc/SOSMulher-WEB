<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

$user = \App\Models\User::where('email', 'raven.oficial.tcc@gmail.com')->first();

echo "Testing password: 'RavenTcc_118'\n";
if (Hash::check('RavenTcc_118', $user->password)) {
    echo "SENHA CORRETA!\n";
} else {
    echo "SENHA INCORRETA\n";
    echo "Hash no banco: " . $user->password . "\n";
    echo "Hash de 'RavenTcc_118': " . Hash::make('RavenTcc_118') . "\n";
}

// Try login
$credentials = ['email' => 'raven.oficial.tcc@gmail.com', 'password' => 'RavenTcc_118'];
if (Auth::attempt($credentials)) {
    echo "LOGIN SUCESSO!\n";
} else {
    echo "LOGIN FALHOU\n";
}