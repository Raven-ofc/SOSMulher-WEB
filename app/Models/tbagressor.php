<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbagressor extends Model
{
    protected $table = 'tbAgressor';
    protected $primaryKey = 'idAgressor';

    protected $fillable = [
        'nomeAgressor',
        'cpfAgressor',
        'logradouroAgressor',
        'numLogradouroAgressor',
        'cepAgressor',
        'bairroAgressor',
        'cidadeAgressor',
        'ufAgressor',
        'complementoAgressor',
        'dataNascAgressor',
        'statusAgressor',
    ];
}
