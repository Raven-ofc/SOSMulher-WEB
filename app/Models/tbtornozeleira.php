<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTornozeleira extends Model
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
        return $this->hasOne(TbAgressor::class, 'idTornozeleira');
    }

    public function localizacoes()
    {
        return $this->hasMany(TbLocalizacaoTornozeleira::class, 'idTornozeleira');
    }
}