<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbAlerta extends Model
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
        return $this->belongsTo(TbViolacao::class, 'idViolacao');
    }

    public function autoridade()
    {
        return $this->belongsTo(TbAutoridade::class, 'idAutoridade');
    }
}