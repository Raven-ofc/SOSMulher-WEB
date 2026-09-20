<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbVitimaGuardiao extends Model
{
    protected $table = 'tbvitimaguardiao';

    protected $fillable = [
        'idVitima',
        'idGuardiao',
    ];

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }

    public function guardiao()
    {
        return $this->belongsTo(TbGuardiao::class, 'idGuardiao');
    }
}