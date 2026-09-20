<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbOcorrencia extends Model
{
    protected $table = 'tbocorrencia';

    protected $fillable = [
        'dataOcorrencia',
        'dataHoraOcorrencia',
        'descricaoOcorrencia',
        'tipoOcorrencia',
        'gravidadeOcorrencia',
        'localOcorrencia',
        'bairroOcorrencia',
        'statusAtendimento',
        'idVitima',
        'idAgressor',
        'idAutoridade',
    ];

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(TbAgressor::class, 'idAgressor');
    }

    public function autoridade()
    {
        return $this->belongsTo(TbAutoridade::class, 'idAutoridade');
    }
}