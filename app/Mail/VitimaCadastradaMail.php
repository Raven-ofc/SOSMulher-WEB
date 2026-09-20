<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VitimaCadastradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nome;
    public string $senha;

    public function __construct(string $nome, string $senha)
    {
        $this->nome = $nome;
        $this->senha = $senha;
    }

    public function build()
    {
        return $this
            ->subject('Cadastro realizado - SOSMulher')
            ->view('email.vitima-cadastrada');
    }
}