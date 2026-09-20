<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbGuardiao extends Model
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
        return $this->hasMany(TbTelefoneGuardiao::class, 'idGuardiao');
    }

    public function vitimas()
    {
        return $this->belongsToMany(TbVitima::class, 'tbvitimaguardiao', 'idGuardiao', 'idVitima');
    }
}