<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneVitima extends Model
{
    protected $table = 'tbtelefonevitima';

    protected $fillable = [
        'numeroTelefoneVitima',
        'idVitima',
    ];

    public function vitima()
    {
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }
}
