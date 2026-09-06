<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbvitimaGuardiao extends Model
{
    protected $table = 'tbvitimaguardiao';

    protected $fillable = [
        'idVitima',
        'idGuardiao',
    ];

    public function vitima()
    {
        return $this->belongsTo(tbvitima::class, 'idVitima');
    }

    public function guardiao()
    {
        return $this->belongsTo(tbguardiao::class, 'idGuardiao');
    }
}
