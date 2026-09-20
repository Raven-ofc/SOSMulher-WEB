<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = \App\Models\User::where('email', 'raven.oficial.tcc@gmail.com')->first();
echo "Email: " . $u->email . "\n";
echo "Senha hash: " . $u->password . "\n";