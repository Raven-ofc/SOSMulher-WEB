<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbSolicitacao extends Model
{
    protected $table = 'tbsolicitacao';

    protected $fillable = [
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
        'analisadoPor',
        'removidoEm',
        'idVitima',
        'idAutoridade',
    ];

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }

    public function autoridade()
    {
        return $this->belongsTo(TbAutoridade::class, 'idAutoridade');
    }
}