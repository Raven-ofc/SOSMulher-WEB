<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbocorrencia extends Model
{
    protected $table = 'tbOcorrencia';
    protected $primaryKey = 'idOcorrencia';

    protected $fillable = [
        'dataHoraOcorrencia',
        'descricaoOcorrencia',
        'tipoOcorrencia',
        'gravidadeOcorrencia',
        'idAutoridade',
        'idVitima',
        'idAgressor',
    ];

    public function autoridade()
    {
        return $this->belongsTo(Autoridade::class, 'idAutoridade', 'idAutoridade');
    }

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(Agressor::class, 'idAgressor', 'idAgressor');
    }
}
