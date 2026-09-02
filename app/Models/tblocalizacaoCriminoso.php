<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tblocalizacaoCriminoso extends Model
{
    protected $table = 'tbLocalizacaoCriminoso';
    protected $primaryKey = 'idLocalizacaoCriminoso';

    protected $fillable = [
        'latitudeLocalizacaoAgressor',
        'longitudeLocalizacaoAgressor',
        'dataHoraLocalizacaoAgressor',
        'idAgressor',
    ];

    public function agressor()
    {
        return $this->belongsTo(Agressor::class, 'idAgressor', 'idAgressor');
    }
}
