<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtornozeleira extends Model
{
    protected $table = 'tbtornozeleira';

    protected $fillable = [
        'numeroSerieTornozeleira',
        'statusTornozeleira',
        'dataInstalacaoTornozeleira',
        'bateriaTornozeleira',
    ];

    public function agressor()
    {
        return $this->hasOne(tbagressor::class, 'idTornozeleira');
    }

    public function localizacoes()
    {
        return $this->hasMany(tblocalizacaoTornozeleira::class, 'idTornozeleira');
    }
}
