<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = \App\Models\User::where('email', 'raven.oficial.tcc@gmail.com')->first();
if ($u) {
    echo "Existe na tabela users:\n";
    echo "  ID: {$u->id}\n";
    echo "  Email: {$u->email}\n";
    echo "  Name: {$u->name}\n";
} else {
    echo "NÃO existe na tabela users\n";
}