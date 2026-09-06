<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbenderecoVitima extends Model
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
        'idVitima',
    ];

    public function vitima()
    {
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }
}
