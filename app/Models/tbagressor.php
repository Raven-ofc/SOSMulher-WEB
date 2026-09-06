<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbagressor extends Model
{
    protected $table = 'tbagressor';

    protected $fillable = [
        'imagemAgressor',
        'nomeAgressor',
        'cpfAgressor',
        'logradouroAgressor',
        'numLogradouroAgressor',
        'bairroAgressor',
        'cidadeAgressor',
        'ufAgressor',
        'complementoAgressor',
        'dataNascimentoAgressor',
        'statusAgressor',
        'idTornozeleira',
    ];

    public function tornozeleira()
    {
        return $this->belongsTo(tbtornozeleira::class, 'idTornozeleira');
    }

    public function ocorrencias()
    {
        return $this->hasMany(tbocorrencia::class, 'idAgressor');
    }

    public function medidas()
    {
        return $this->hasMany(tbmedida::class, 'idAgressor');
    }
}
