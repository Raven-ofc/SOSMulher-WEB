<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbAgressor extends Model
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
        return $this->belongsTo(TbTornozeleira::class, 'idTornozeleira');
    }

    public function ocorrencias()
    {
        return $this->hasMany(TbOcorrencia::class, 'idAgressor');
    }

    public function medidas()
    {
        return $this->hasMany(TbMedida::class, 'idAgressor');
    }
}