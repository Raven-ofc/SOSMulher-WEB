<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbvitima extends Model
{
    protected $table = 'tbvitima';

    protected $fillable = [
        'imagemVitima',
        'nomeVitima',
        'cpfVitima',
        'emailVitima',
        'senhaVitima',
        'dataNascimentoVitima',
        'statusVitima',
    ];

    public function telefones()
    {
        return $this->hasMany(tbtelefoneVitima::class, 'idVitima');
    }

    public function enderecos()
    {
        return $this->hasMany(tbenderecoVitima::class, 'idVitima');
    }

    public function localizacoes()
    {
        return $this->hasMany(tblocalizacaoVitima::class, 'idVitima');
    }

    public function ocorrencias()
    {
        return $this->hasMany(tbocorrencia::class, 'idVitima');
    }

    public function medidas()
    {
        return $this->hasMany(tbmedida::class, 'idVitima');
    }

    public function solicitacoes()
    {
        return $this->hasMany(tbsolicitacao::class, 'idVitima');
    }

    public function guardioes()
    {
        return $this->belongsToMany(tbguardiao::class, 'tbvitimaguardiao', 'idVitima', 'idGuardiao');
    }
}
