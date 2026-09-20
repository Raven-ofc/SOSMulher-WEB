<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Test the actual hash
$user = \App\Models\User::where('email', 'raven.oficial.tcc@gmail.com')->first();

echo "User found: " . ($user ? "YES" : "NO") . "\n";
if ($user) {
    echo "Email: " . $user->email . "\n";
    echo "Password hash: " . $user->password . "\n";
    
    // Test with common passwords
    $testPasswords = ['password', '123456', 'admin', 'senha', 'raven', 'tcc', 'sosmulher', 'SOSMulher', 'SOSMULHER', '12345678', 'admin123'];
    
    foreach ($testPasswords as $pwd) {
        if (Hash::check($pwd, $user->password)) {
            echo "SENHA CORRETA ENCONTRADA: '$pwd'\n";
            break;
        }
    }
    
    // Try to login programmatically
    $credentials = ['email' => 'raven.oficial.tcc@gmail.com', 'password' => 'password'];
    echo "\nTentando login com 'password': ";
    if (Auth::attempt($credentials)) {
        echo "SUCESSO!\n";
    } else {
        echo "FALHOU\n";
    }
    
    // Try with other common passwords
    foreach ($testPasswords as $pwd) {
        $credentials = ['email' => 'raven.oficial.tcc@gmail.com', 'password' => $pwd];
        if (Auth::attempt($credentials)) {
            echo "SUCESSO com '$pwd'!\n";
            break;
        }
    }
}