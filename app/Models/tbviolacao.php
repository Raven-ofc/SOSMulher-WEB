<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbviolacao extends Model
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
        return $this->belongsTo(tbmedida::class, 'idMedida');
    }

    public function alertas()
    {
        return $this->hasMany(tbalerta::class, 'idViolacao');
    }
}
