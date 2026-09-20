<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbLocalizacaoTornozeleira extends Model
{
    protected $table = 'tblocalizacaotornozeleira';

    protected $fillable = [
        'idTornozeleira',
        'latitudeLocalizacao',
        'longitudeLocalizacao',
        'dataHoraLocalizacao',
    ];

    public function tornozeleira()
    {
        return $this->belongsTo(TbTornozeleira::class, 'idTornozeleira');
    }
}