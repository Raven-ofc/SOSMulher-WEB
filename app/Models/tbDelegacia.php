<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbDelegacia extends Model
{
    protected $table = 'tbdelegacia';

    protected $fillable = [
        'nomeDelegacia',
        'tipoDelegacia',
        'statusDelegacia',
        'logradouroDelegacia',
        'numLogradouroDelegacia',
        'bairroDelegacia',
        'cidadeDelegacia',
        'ufDelegacia',
        'cepDelegacia',
        'longitudeDelegacia',
        'latitudeDelegacia'
    ];

    public function telefones()
    {
        return $this->hasMany(tbtelefonedelegacia::class, 'idDelegacia');
    }
}
