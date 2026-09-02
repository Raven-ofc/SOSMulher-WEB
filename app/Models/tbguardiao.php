<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbguardiao extends Model
{
    protected $table = 'tbGuardiao';
    protected $primaryKey = 'idGuardiao';

    protected $fillable = [
        'imagemGuardiao',
        'nomeGuardiao',
        'emailGuardiao',
        'senhaGuardiao',
        'cpfGuardiao',
        'dataNascGuardiao',
        'statusGuardiao',
    ];
}
