<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tbvitimaGuardiao extends Model
{
    protected $table = 'tbVitimaGuardiao';
    protected $primaryKey = 'idVitimaGuardiao';

    protected $fillable = [
        'idVitima',
        'idGuardiao',
    ];

    public function vitima()
    {
        return $this->belongsTo(Vitima::class, 'idVitima', 'idVitima');
    }

    public function guardiao()
    {
        return $this->belongsTo(Guardiao::class, 'idGuardiao', 'idGuardiao');
    }
}
