<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Password;

// Test password reset link
$email = 'teste@autoridade.com';
$status = Password::sendResetLink(['emailAutoridade' => $email]);

echo "Password reset status: $status\n";
echo "Status constant: " . Password::RESET_LINK_SENT . "\n";