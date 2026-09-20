<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTelefoneGuardiao extends Model
{
    protected $table = 'tbtelefoneguardiao';

    protected $fillable = [
        'numeroTelefoneGuardiao',
        'idGuardiao',
    ];

    public function guardiao()
    {
        return $this->belongsTo(TbGuardiao::class, 'idGuardiao');
    }
}