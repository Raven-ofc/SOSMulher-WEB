<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneVitima extends Model
{
    protected $table = 'tbTelefoneVitima';
    protected $primaryKey = 'idTelefoneVitima';

    protected $fillable = [
        'numTelefoneVitima',
        'idVitima',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }
}
