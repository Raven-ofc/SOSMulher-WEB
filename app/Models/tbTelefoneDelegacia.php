<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbTelefoneDelegacia extends Model
{
    protected $table = 'tbtelefonedelegacia';

    protected $fillable = [
        'numTelefoneDelegacia',
        'idDelegacia'
    ];

    public function telefones()
    {
        return $this->hasMany(tbtelefonedelegacia::class, 'idDelegacia');
    }
}
