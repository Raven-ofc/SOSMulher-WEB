<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbautoridade extends Model
{
    protected $table = 'tbautoridade';

    protected $fillable = [
        'imagemAutoridade',
        'nomeAutoridade',
        'emailAutoridade',
        'cpfAutoridade',
        'matriculaAutoridade',
        'cargoAutoridade',
        'unidadeAutoridade',
        'senhaAutoridade',
        'statusAutoridade',
    ];

    public function telefones()
    {
        return $this->hasMany(tbtelefoneAutoridade::class, 'idAutoridade');
    }

    public function ocorrencias()
    {
        return $this->hasMany(tbocorrencia::class, 'idAutoridade');
    }

    public function solicitacoes()
    {
        return $this->hasMany(tbsolicitacao::class, 'idAutoridade');
    }

    public function alertas()
    {
        return $this->hasMany(tbalerta::class, 'idAutoridade');
    }
}
