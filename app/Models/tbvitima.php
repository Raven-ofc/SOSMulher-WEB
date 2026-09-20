<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbVitima extends Model
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
        return $this->hasMany(TbTelefoneVitima::class, 'idVitima');
    }

    public function enderecos()
    {
        return $this->hasMany(TbEnderecoVitima::class, 'idVitima');
    }

    public function localizacoes()
    {
        return $this->hasMany(TbLocalizacaoVitima::class, 'idVitima');
    }

    public function ocorrencias()
    {
        return $this->hasMany(TbOcorrencia::class, 'idVitima');
    }

    public function medidas()
    {
        return $this->hasMany(TbMedida::class, 'idVitima');
    }

    public function solicitacoes()
    {
        return $this->hasMany(TbSolicitacao::class, 'idVitima');
    }

    public function guardioes()
    {
        return $this->belongsToMany(TbGuardiao::class, 'tbvitimaguardiao', 'idVitima', 'idGuardiao');
    }
}