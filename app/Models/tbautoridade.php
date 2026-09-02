<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbautoridade extends Model
{
    protected $table = 'tbAutoridade';
    protected $primaryKey = 'idAutoridade';

    protected $fillable = [
        'imagemAutoridade',
        'nomeAutoridade',
        'matriculaAutoridade',
        'cargoAutoridade',
        'emailAutoridade',
        'senhaAutoridade',
        'unidadeAutoridade',
        'statusAutoridade',
    ];

    // Relacionamento: uma autoridade pode ter vários telefones
    public function telefones()
    {
        return $this->hasMany(TelefoneAutoridade::class, 'idAutoridade', 'idAutoridade');
    }
}
