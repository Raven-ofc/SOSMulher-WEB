<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbLocalizacaoVitima extends Model
{
    protected $table = 'tblocalizacaovitima';

    protected $fillable = [
        'idVitima',
        'latitudeLocalizacao',
        'longitudeLocalizacao',
        'dataHoraLocalizacao',
    ];

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }
}