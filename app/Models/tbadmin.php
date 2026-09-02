<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbadmin extends Model
{
    protected $table = 'tbAdmin';
    protected $primaryKey = 'idAdmin';

    protected $fillable = [
        'nomeAdmin',
        'emailAdmin',
        'senhaAdmin',
        'cpfAdmin',
        'dataNascAdmin',
    ];

    public function telefones()
    {
        return $this->hasMany(TelefoneAdmin::class, 'idAdmin', 'idAdmin');
    }
}
