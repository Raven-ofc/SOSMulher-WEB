<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbEnderecoVitima extends Model
{
    protected $table = 'tbenderecovitima';

    protected $fillable = [
        'logradouroVitima',
        'numLogradouroVitima',
        'bairroVitima',
        'cidadeVitima',
        'ufVitima',
        'complementoVitima',
        'cepVitima',
        'longitudeVitima',
        'latitudeVitima',
        'tipoEndereco',
        'idVitima',
    ];

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }
}