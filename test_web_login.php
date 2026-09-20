<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Simulate login request
$request = Request::create('/login', 'POST', [
    'email' => 'teste@autoridade.com',
    'password' => 'senha123',
]);

$app->instance('request', $request);

// Test the AcessoController logic
$credentials = $request->validate([
    'email' => ['required', 'email'],
    'password' => ['required', 'string'],
]);

$authCredentials = [
    'emailAutoridade' => $credentials['email'],
    'password' => $credentials['password'],
];

echo "Attempting login with: " . json_encode($authCredentials) . "\n";

if (Auth::attempt($authCredentials)) {
    echo "LOGIN SUCESSO!\n";
    echo "User: " . Auth::user()->nomeAutoridade . "\n";
    echo "Email: " . Auth::user()->emailAutoridade . "\n";
} else {
    echo "LOGIN FALHOU\n";
}