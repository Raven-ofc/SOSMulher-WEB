<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbalerta extends Model
{
    protected $table = 'tbalerta';

    protected $fillable = [
        'tipoAlerta',
        'descricaoAlerta',
        'dataHoraAlerta',
        'statusAlerta',
        'idViolacao',
        'idAutoridade',
    ];

    public function violacao()
    {
        return $this->belongsTo(tbviolacao::class, 'idViolacao');
    }

    public function autoridade()
    {
        return $this->belongsTo(tbautoridade::class, 'idAutoridade');
    }
}
