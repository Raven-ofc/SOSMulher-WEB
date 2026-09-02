<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneGuardiao extends Model
{
    protected $table = 'tbTelefoneGuardiao';
    protected $primaryKey = 'idTelefoneGuardiao';

    protected $fillable = [
        'numTelefoneGuardiao',
        'idGuardiao',
    ];

    public function guardiao()
    {
        return $this->belongsTo(Guardiao::class, 'idGuardiao', 'idGuardiao');
    }
}
