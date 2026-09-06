<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbmedida extends Model
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
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(tbagressor::class, 'idAgressor');
    }

    public function violacoes()
    {
        return $this->hasMany(tbviolacao::class, 'idMedida');
    }
}
