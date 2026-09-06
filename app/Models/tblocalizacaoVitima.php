<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tblocalizacaoVitima extends Model
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
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }
}
