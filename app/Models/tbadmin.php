<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbAdmin extends Model
{
    protected $table = 'tbadmin';

    protected $fillable = [
        'nomeAdmin',
        'emailAdmin',
        'senhaAdmin',
        'cpfAdmin',
        'dataNascAdmin',
    ];

    public function telefones()
    {
        return $this->hasMany(TbTelefoneAdmin::class, 'idAdmin');
    }
}