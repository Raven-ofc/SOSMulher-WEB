<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbocorrencia extends Model
{
    protected $table = 'tbocorrencia';

    protected $fillable = [
        'dataOcorrencia',
        'descricaoOcorrencia',
        'tipoOcorrencia',
        'gravidadeOcorrencia',
        'idVitima',
        'idAgressor',
        'idAutoridade',
    ];

    public function vitima()
    {
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(tbagressor::class, 'idAgressor');
    }

    public function autoridade()
    {
        return $this->belongsTo(tbautoridade::class, 'idAutoridade');
    }
}
