<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tbautoridade extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbautoridade';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'imagemAutoridade',
        'nomeAutoridade',
        'emailAutoridade',
        'cpfAutoridade',
        'matriculaAutoridade',
        'cargoAutoridade',
        'unidadeAutoridade',
        'senhaAutoridade',
        'statusAutoridade',
    ];

    protected $hidden = [
        'senhaAutoridade',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'senhaAutoridade' => 'hashed',
            'matriculaAutoridade' => 'date',
        ];
    }

    public function getAuthIdentifierName()
    {
        return 'emailAutoridade';
    }

    public function getAuthPassword()
    {
        return $this->senhaAutoridade;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getEmailForPasswordReset()
    {
        return $this->emailAutoridade;
    }

    public function telefones()
    {
        return $this->hasMany(tbtelefoneAutoridade::class, 'idAutoridade');
    }

    public function ocorrencias()
    {
        return $this->hasMany(tbocorrencia::class, 'idAutoridade');
    }

    public function solicitacoes()
    {
        return $this->hasMany(tbsolicitacao::class, 'idAutoridade');
    }

    public function alertas()
    {
        return $this->hasMany(tbalerta::class, 'idAutoridade');
    }
}
