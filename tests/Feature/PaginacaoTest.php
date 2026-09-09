<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class PaginacaoTest extends TestCase
{
    public function test_paginacao_preserva_filtros_e_indica_pagina_atual(): void
    {
        $paginador = new LengthAwarePaginator(range(16, 30), 40, 15, 2, [
            'path' => '/administracao/vitimas',
            'query' => ['search' => 'teste'],
        ]);

        $html = (string) $paginador->links();

        $this->assertStringContainsString('class="paginacao"', $html);
        $this->assertStringContainsString('aria-current="page">2', $html);
        $this->assertStringContainsString('search=teste&amp;page=1', $html);
        $this->assertStringContainsString('search=teste&amp;page=3', $html);
    }

    public function test_lista_com_uma_pagina_nao_exibe_navegacao(): void
    {
        $paginador = new LengthAwarePaginator([1], 1, 15);

        $this->assertSame('', trim((string) $paginador->links()));
    }
}
