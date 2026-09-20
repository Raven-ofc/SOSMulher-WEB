<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\tbautoridade;

// Test full password reset flow
$email = 'teste@autoridade.com';
$user = tbautoridade::where('emailAutoridade', $email)->first();

echo "User before reset: " . $user->senhaAutoridade . "\n";

// Send reset link
$status = Password::sendResetLink(['emailAutoridade' => $email]);
echo "Send reset status: $status\n";

// Get the token from database
$tokenRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
if ($tokenRecord) {
    echo "Token found: " . $tokenRecord->token . "\n";
    
    // Test reset
    $newPassword = 'novaSenha123';
    $status = Password::reset(
        ['emailAutoridade' => $email, 'password' => $newPassword, 'password_confirmation' => $newPassword, 'token' => $tokenRecord->token],
        function (tbautoridade $user, string $password) {
            $user->forceFill([
                'senhaAutoridade' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        }
    );
    
    echo "Reset status: $status\n";
    
    // Verify new password works
    $user->refresh();
    echo "User after reset: " . $user->senhaAutoridade . "\n";
    
    if (Hash::check($newPassword, $user->senhaAutoridade)) {
        echo "NOVA SENHA FUNCIONA!\n";
    } else {
        echo "Nova senha NÃO funciona\n";
    }
}