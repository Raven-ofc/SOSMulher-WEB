<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_registered_user_receives_a_reset_notification(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post(route('password.email'), ['email' => $user->email])->assertRedirect(route('password.email.sent'));
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_invalid_token_cannot_change_password(): void
    {
        $user = User::factory()->create();
        $hash = $user->password;
        $this->post(route('password.update'), [
            'email' => $user->email, 'token' => 'invalid',
            'password' => 'NewPassword!123', 'password_confirmation' => 'NewPassword!123',
        ])->assertSessionHasErrors('password');
        $this->assertSame($hash, $user->fresh()->password);
    }

    public function test_valid_token_changes_password_and_cannot_be_reused(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $payload = ['email' => $user->email, 'token' => $token, 'password' => 'NewPassword!123', 'password_confirmation' => 'NewPassword!123'];
        $this->post(route('password.update'), $payload)->assertRedirect(route('login'));
        $this->assertTrue(Hash::check($payload['password'], $user->fresh()->password));
        $this->post(route('password.update'), $payload)->assertSessionHasErrors('password');
    }

    public function test_confirmation_is_required_and_success_page_requires_request(): void
    {
        $this->get(route('password.email.sent'))->assertRedirect(route('password.request'));
        $this->post(route('password.update'), ['email' => 'test@example.test', 'token' => 'invalid', 'password' => 'NewPassword!123'])
            ->assertSessionHasErrors('password');
    }
}
