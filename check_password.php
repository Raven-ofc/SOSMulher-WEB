<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;

$hash = '$2y$12$Vhzc8B/9O.iGoqNwRITkter21zP886sCVUZanPf7lokBFEPbYQcnK';

// Test common passwords
$testPasswords = ['password', '123456', 'admin', 'senha', 'raven', 'tcc', 'sosmulher', 'SOSMulher', 'SOSMULHER'];

foreach ($testPasswords as $pwd) {
    if (Hash::check($pwd, $hash)) {
        echo "SENHA ENCONTRADA: '$pwd'\n";
        exit;
    }
}
echo "Senha nao encontrada nas tentativas comuns\n";