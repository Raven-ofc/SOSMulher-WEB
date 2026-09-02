<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneAutoridade extends Model
{
    protected $table = 'tbTelefoneAutoridade';
    protected $primaryKey = 'idTelefoneAutoridade';

    protected $fillable = [
        'numTelefoneAutoridade',
        'idAutoridade',
    ];

    public function autoridade()
    {
        return $this->belongsTo(Autoridade::class, 'idAutoridade', 'idAutoridade');
    }
}
