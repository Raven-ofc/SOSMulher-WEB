<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbOcorrencia extends Model
{
    public const STATUS_ABERTA = 'aberta';
    public const STATUS_ANDAMENTO = 'andamento';
    public const STATUS_ENCERRADA = 'realizado';

    /** status => [rótulo exibido, classe CSS de cor]. Vermelho = recém aberta, amarelo = em andamento, verde = encerrada. */
    public const STATUS = [
        self::STATUS_ABERTA => ['Aberta', 'aberta'],
        self::STATUS_ANDAMENTO => ['Em andamento', 'andamento'],
        self::STATUS_ENCERRADA => ['Encerrada', 'encerrada'],
    ];

    protected $table = 'tbocorrencia';

    protected $fillable = [
        'dataOcorrencia',
        'dataHoraOcorrencia',
        'descricaoOcorrencia',
        'tipoOcorrencia',
        'gravidadeOcorrencia',
        'cep',
        'localOcorrencia',
        'bairroOcorrencia',
        'latitudeOcorrencia',
        'longitudeOcorrencia',
        'statusAtendimento',
        'idVitima',
        'idAgressor',
        'idAutoridade',
    ];

    public static function statusValidos(): array
    {
        return array_keys(self::STATUS);
    }

    public function statusRotulo(): string
    {
        return self::STATUS[$this->statusAtendimento][0] ?? ucfirst((string) $this->statusAtendimento);
    }

    public function statusClasse(): string
    {
        return self::STATUS[$this->statusAtendimento][1] ?? 'neutro';
    }

    public function estaEncerrada(): bool
    {
        return $this->statusAtendimento === self::STATUS_ENCERRADA;
    }

    public function vitima()
    {
        return $this->belongsTo(TbVitima::class, 'idVitima');
    }

    public function agressor()
    {
        return $this->belongsTo(TbAgressor::class, 'idAgressor');
    }

    public function autoridade()
    {
        return $this->belongsTo(TbAutoridade::class, 'idAutoridade');
    }
}
