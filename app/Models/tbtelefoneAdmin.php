<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbtelefoneAdmin extends Model
{
    protected $table = 'tbtelefoneadmin';

    protected $fillable = [
        'numTelefoneAdmin',
        'idAdmin',
    ];

    public function admin()
    {
        return $this->belongsTo(tbadmin::class, 'idAdmin');
    }
}
