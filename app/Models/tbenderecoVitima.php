<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbenderecoVitima extends Model
{
    protected $table = 'tbEnderecovitima';
    protected $primaryKey = 'idEnderecovitima';

    protected $fillable = [
        'logradouroVitima',
        'numLogradouroVitima',
        'cepVitima',
        'bairroVitima',
        'cidadeVitima',
        'complementoVitima',
        'ufVitima',
        'latitudeVitima',
        'longitudeVitima',
        'idVitima',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }
}
