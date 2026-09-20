<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbMedida extends Model
{
    protected $table = 'tbmedida';

    protected $fillable = [
        'dataInicioMedida',
        'dataFimMedida',
        'distanciaMedida',
        'raioAlertaMedida',
        'descricaoMedida',
        'statusMedida',
        'idVitima',
        'idAgressor',
    ];

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(TbAgressor::class, 'idAgressor');
    }

    public function violacoes()
    {
        return $this->hasMany(TbViolacao::class, 'idMedida');
    }
}