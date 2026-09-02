<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbviolacao extends Model
{
    protected $table = 'tbViolacao';
    protected $primaryKey = 'idViolacao';

    protected $fillable = [
        'tipoViolacao',
        'descricaoViolacao',
        'latitudeViolacao',
        'longitudeViolacao',
        'statusViolacao',
        'idVitima',
        'idMedida',
        'idAgressor',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }

    public function medida()
    {
        return $this->belongsTo(Medida::class, 'idMedida', 'idMedida');
    }

    public function agressor()
    {
        return $this->belongsTo(Agressor::class, 'idAgressor', 'idAgressor');
    }
}
