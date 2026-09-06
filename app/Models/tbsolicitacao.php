<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbsolicitacao extends Model
{
    protected $table = 'tbsolicitacao';

    protected $fillable = [
        'descricaoSolicitacao',
        'tipoSolicitacao',
        'logradouroSolicitacao',
        'numLogradouroSolicitacao',
        'bairroSolicitacao',
        'cidadeSolicitacao',
        'ufSolicitacao',
        'complementoSolicitacao',
        'cepSolicitacao',
        'latitudeSolicitacao',
        'longitudeSolicitacao',
        'statusSolicitacao',
        'dataSolicitacao',
        'dataAnalise',
        'idVitima',
        'idAutoridade',
    ];

    public function vitima()
    {
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }

    public function autoridade()
    {
        return $this->belongsTo(tbautoridade::class, 'idAutoridade');
    }
}
