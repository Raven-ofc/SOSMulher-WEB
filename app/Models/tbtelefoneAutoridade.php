<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbTelefoneAutoridade extends Model
{
    protected $table = 'tbtelefoneautoridade';

    protected $fillable = [
        'numTelefoneAutoridade',
        'idAutoridade',
    ];

    public function autoridade()
    {
        return $this->belongsTo(TbAutoridade::class, 'idAutoridade');
    }
}