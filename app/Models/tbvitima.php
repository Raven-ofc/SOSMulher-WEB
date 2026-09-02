<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbvitima extends Model
{
    protected $table = 'tbVitima';
    protected $primaryKey = 'idVitima';

    protected $fillable = [
        'imagemVitima',
        'nomeVitima',
        'cpfVitima',
        'emailVitima',
        'senhaVitima',
        'dataNascVitima',
        'statusVitima',
        'latitudeVitima',
        'longitudeVitima',
    ];
}
