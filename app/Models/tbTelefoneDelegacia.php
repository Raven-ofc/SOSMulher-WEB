<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTelefoneDelegacia extends Model
{
    protected $table = 'tbtelefonedelegacia';

    protected $fillable = [
        'numTelefoneDelegacia',
        'idDelegacia'
    ];

    public function delegacia()
    {
        return $this->belongsTo(TbDelegacia::class, 'idDelegacia');
    }
}
