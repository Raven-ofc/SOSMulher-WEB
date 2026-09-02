<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbmedida extends Model
{
    protected $table = 'tbMedida';
    protected $primaryKey = 'idMedida';

    protected $fillable = [
        'dataInicioMedida',
        'dataFimMedida',
        'distanciaMaximaMedida',
        'statusMedida',
        'idVitima',
        'idAgressor',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(Agressor::class, 'idAgressor', 'idAgressor');
    }
}
