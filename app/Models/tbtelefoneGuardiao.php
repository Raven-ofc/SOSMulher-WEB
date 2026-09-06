<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneGuardiao extends Model
{
    protected $table = 'tbtelefoneguardiao';

    protected $fillable = [
        'numeroTelefoneGuardiao',
        'idGuardiao',
    ];

    public function guardiao()
    {
        return $this->belongsTo(tbguardiao::class, 'idGuardiao');
    }
}
