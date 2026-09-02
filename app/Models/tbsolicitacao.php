<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbsolicitacao extends Model
{
    protected $table = 'tbSolicitacao';
    protected $primaryKey = 'idSolicitacao';

    protected $fillable = [
        'tipoSolicitacao',
        'statusSolicitacao',
        'descricaoSolicitacao',
        'logradouroSolicitacao',
        'numLogradouroSolicitacao',
        'cepSolicitacao',
        'bairroSolicitacao',
        'cidadeSolicitacao',
        'complementoSolicitacao',
        'ufSolicitacao',
        'latitudeSolicitacao',
        'longitudeSolicitacao',
        'dataCriacaoSolicitacao',
        'dataAnaliseSolicitacao',
        'idVitima',
        'idEnderecovitima',
        'idAutoridade',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }

    public function enderecoVitima()
    {
        return $this->belongsTo(EnderecoVitima::class, 'idEnderecovitima', 'idEnderecovitima');
    }

    public function autoridade()
    {
        return $this->belongsTo(Autoridade::class, 'idAutoridade', 'idAutoridade');
    }
}
