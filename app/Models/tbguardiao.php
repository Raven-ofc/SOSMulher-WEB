<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbguardiao extends Model
{
    protected $table = 'tbguardiao';

    protected $fillable = [
        'imagemGuardiao',
        'nomeGuardiao',
        'cpfGuardiao',
        'emailGuardiao',
        'senhaGuardiao',
        'dataNascimentoGuardiao',
        'statusGuardiao',
    ];

    public function telefones()
    {
        return $this->hasMany(tbtelefoneGuardiao::class, 'idGuardiao');
    }

    public function vitimas()
    {
        return $this->belongsToMany(tbvitima::class, 'tbvitimaguardiao', 'idGuardiao', 'idVitima');
    }
}
