<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbViolacao extends Model
{
    protected $table = 'tbviolacao';

    protected $fillable = [
        'tipoViolacao',
        'descricaoViolacao',
        'statusViolacao',
        'latitudeViolacao',
        'longitudeViolacao',
        'dataHoraViolacao',
        'idMedida',
    ];

    public function medida()
    {
        return $this->belongsTo(TbMedida::class, 'idMedida');
    }

    public function alertas()
    {
        return $this->hasMany(TbAlerta::class, 'idViolacao');
    }
}