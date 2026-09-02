<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbalerta extends Model
{
    protected $table = 'tbAlerta';
    protected $primaryKey = 'idAlerta';

    protected $fillable = [
        'tipoAlerta',
        'dataHoraAlerta',
        'mensagemAlerta',
        'statusAlerta',
        'idVitima',
        'idAutoridade',
        'idViolacao',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }

    public function autoridade()
    {
        return $this->belongsTo(Autoridade::class, 'idAutoridade', 'idAutoridade');
    }

    public function violacao()
    {
        return $this->belongsTo(Violacao::class, 'idViolacao', 'idViolacao');
    }
}
