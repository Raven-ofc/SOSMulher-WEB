<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneAdmin extends Model
{
    protected $table = 'tbTelefoneAdmin';
    protected $primaryKey = 'idTelefoneAdmin';

    protected $fillable = [
        'numTelefoneAdmin',
        'idAdmin',
    ];

    // Relacionamento inverso: o telefone pertence a um admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'idAdmin');
    }
}
