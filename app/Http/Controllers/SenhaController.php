<?php

namespace App\Http\Controllers;

use App\Models\tbautoridade;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class SenhaController extends Controller
{
    public function email(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);

        if (in_array(config('mail.default'), ['log', 'array'], true) && ! app()->runningUnitTests()) {
            return back()->withErrors(['email' => 'O envio de e-mail ainda não foi configurado. Entre em contato com a administração.'])->onlyInput('email');
        }

        try {
            $status = Password::sendResetLink(['emailAutoridade' => $request->email]);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors(['email' => 'Não foi possível enviar a mensagem. Tente novamente mais tarde.'])->onlyInput('email');
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors(['email' => 'Aguarde antes de solicitar outro link.'])->onlyInput('email');
        }

        return redirect()->route('password.email.sent')->with('reset_requested', true);
    }

    public function sent()
    {
        if (! session('reset_requested')) {
            return redirect()->route('password.request');
        }

        return view('autenticacao.email-enviado');
    }

    public function edit(Request $request)
    {
        if (! $request->filled('token') || ! $request->filled('email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Abra o link de recuperação recebido por e-mail.']);
        }

        return view('autenticacao.redefinir-senha');
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(12)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (tbautoridade $user, string $password) {
                $user->forceFill([
                    'senhaAutoridade' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['password' => 'Link inválido ou expirado. Solicite um novo link de recuperação.']);
        }

        return redirect()->route('login')->with('status', 'Senha alterada com sucesso. Entre com sua nova senha.');
    }
}
