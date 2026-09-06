<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneAutoridade extends Model
{
    protected $table = 'tbtelefoneautoridade';

    protected $fillable = [
        'numTelefoneAutoridade',
        'idAutoridade',
    ];

    public function autoridade()
    {
        return $this->belongsTo(tbautoridade::class, 'idAutoridade');
    }
}
