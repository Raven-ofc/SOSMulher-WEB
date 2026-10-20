<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbMedida extends Model
{
    /** Tipos de medida protetiva de urgência (Lei 11.340/2006, arts. 22 e 23). */
    public const TIPOS = [
        'afastamento_lar' => 'Afastamento do lar',
        'proibicao_aproximacao' => 'Proibição de aproximação da vítima e familiares',
        'proibicao_contato' => 'Proibição de contato por qualquer meio',
        'proibicao_frequentar' => 'Proibição de frequentar locais (trabalho, escola, residência)',
        'restricao_visitas' => 'Restrição ou suspensão de visitas aos dependentes',
        'monitoramento_eletronico' => 'Monitoramento eletrônico (tornozeleira)',
        'alimentos' => 'Prestação de alimentos provisionais',
    ];

    public const RISCOS = ['baixo' => 'Baixo', 'medio' => 'Médio', 'alto' => 'Alto'];

    protected $table = 'tbmedida';

    protected $fillable = [
        'dataInicioMedida',
        'dataFimMedida',
        'distanciaMedida',
        'raioAlertaMedida',
        'descricaoMedida',
        'numeroProcessoMedida',
        'varaMedida',
        'tiposMedida',
        'nivelRiscoMedida',
        'observacoesMedida',
        'statusMedida',
        'idVitima',
        'idAgressor',
        'idAutoridade',
    ];

    protected $casts = [
        'tiposMedida' => 'array',
    ];

    public function numeroFormatado(): string
    {
        $ano = $this->dataInicioMedida ? substr((string) $this->dataInicioMedida, 0, 4) : date('Y');

        return sprintf('MP-%s-%04d', $ano, $this->id ?? 0);
    }

    public function tiposRotulos(): array
    {
        return collect($this->tiposMedida ?? [])
            ->map(fn ($chave) => self::TIPOS[$chave] ?? $chave)
            ->values()
            ->all();
    }

    public function riscoRotulo(): string
    {
        return self::RISCOS[$this->nivelRiscoMedida] ?? 'Médio';
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

    public function violacoes()
    {
        return $this->hasMany(TbViolacao::class, 'idMedida');
    }
}
