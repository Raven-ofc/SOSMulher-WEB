<?php

namespace Tests\Feature;

use App\Models\TbAutoridade;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_registered_autoridade_receives_a_reset_notification(): void
    {
        Notification::fake();
        $autoridade = TbAutoridade::factory()->create();
        $this->post(route('senha.email'), ['email' => $autoridade->emailAutoridade])->assertRedirect(route('senha.email.enviado'));
        Notification::assertSentTo($autoridade, ResetPassword::class);
    }

    public function test_invalid_token_cannot_change_password(): void
    {
        $autoridade = TbAutoridade::factory()->create();
        $hash = $autoridade->senhaAutoridade;
        $this->post(route('senha.atualizar'), [
            'email' => $autoridade->emailAutoridade, 'token' => 'invalid',
            'senha' => 'NewPassword!123', 'senha_confirmation' => 'NewPassword!123',
        ])->assertSessionHasErrors('senha');
        $this->assertSame($hash, $autoridade->fresh()->senhaAutoridade);
    }

    public function test_valid_token_changes_password_and_cannot_be_reused(): void
    {
        $autoridade = TbAutoridade::factory()->create();
        $token = Password::createToken($autoridade);
        $payload = ['email' => $autoridade->emailAutoridade, 'token' => $token, 'senha' => 'NewPassword!123', 'senha_confirmation' => 'NewPassword!123'];
        $this->post(route('senha.atualizar'), $payload)->assertRedirect(route('login'));
        $this->assertTrue(Hash::check($payload['senha'], $autoridade->fresh()->senhaAutoridade));
        $this->post(route('senha.atualizar'), $payload)->assertSessionHasErrors('senha');
    }

    public function test_confirmation_is_required_and_success_page_requires_request(): void
    {
        $this->get(route('senha.email.enviado'))->assertRedirect(route('senha.solicitar'));
        $this->post(route('senha.atualizar'), ['email' => 'test@example.test', 'token' => 'invalid', 'senha' => 'NewPassword!123'])
            ->assertSessionHasErrors('senha');
    }
}