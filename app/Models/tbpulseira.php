<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbpulseira extends Model
{
    protected $table = 'tbPulseira';
    protected $primaryKey = 'idPulseira';

    protected $fillable = [
        'numeroSerieTornozeleira',
        'statusTornozeleira',
        'dataInstalacaoPulseira',
        'bateriaPulseira',
        'idAgressor',
    ];

    public function agressor()
    {
        return $this->belongsTo(Agressor::class, 'idAgressor', 'idAgressor');
    }
}
