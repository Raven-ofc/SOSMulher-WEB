<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Preenche CEP, latitude e longitude da tabela tbdelegacia.
 *
 *  1. CEP:  ViaCEP, busca por UF + cidade + logradouro. Escolhe o CEP cuja
 *           faixa de numeração contém o número da delegacia; se não houver,
 *           o do mesmo bairro; senão o primeiro da rua (CEP "próximo").
 *           Se o ViaCEP não achar, tenta o postcode devolvido pelo Nominatim.
 *  2. Coordenadas: Nominatim, mesma abordagem do seu buscarCoord()
 *           (q + countrycodes=br), com fallback: endereço completo -> sem bairro
 *           -> só a rua -> só o bairro. Resultados fora do estado de SP são
 *           descartados.
 *
 * Nunca apaga nada por erro de rede/HTTP: só remove quando a consulta
 * funcionou e não devolveu resultado.
 *
 * Exemplos:
 *   php artisan delegacias:geocodificar --dry-run --limite=10
 *   php artisan delegacias:geocodificar
 *   php artisan delegacias:geocodificar --remover-sem-cep
 *   php artisan delegacias:geocodificar --remover-sem-coordenadas
 */
class GeocodificarDelegacias extends Command
{
    protected $signature = 'delegacias:geocodificar
                            {--dry-run : Mostra o que seria feito, sem gravar nem apagar}
                            {--limite=0 : Processa só as N primeiras (para testar)}
                            {--refazer : Refaz as coordenadas mesmo das que já têm}
                            {--remover-sem-cep : Apaga as que continuarem sem CEP}
                            {--remover-sem-coordenadas : Apaga as que o Nominatim não localizar}';

    protected $description = 'Preenche CEP e coordenadas das delegacias (ViaCEP + Nominatim)';

    private const TABELA = 'tbdelegacia';

    // Caixa aproximada do estado de São Paulo, para descartar homônimos de outros estados.
    private const SP_LAT = [-25.35, -19.75];
    private const SP_LON = [-53.15, -44.10];

    private array $cacheCep = [];
    private array $cacheCoord = [];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $refazer = (bool) $this->option('refazer');

        $consulta = DB::table(self::TABELA)->orderBy('id');

        if (!$refazer) {
            $consulta->where(function ($q) {
                $q->whereNull('cepDelegacia')
                    ->orWhereNull('latitudedelegacia')
                    ->orWhereNull('longitudedelegacia');
            });
        }

        if ((int) $this->option('limite') > 0) {
            $consulta->limit((int) $this->option('limite'));
        }

        $delegacias = $consulta->get();

        $this->info("Processando {$delegacias->count()} delegacia(s)" . ($dryRun ? ' [dry-run]' : '') . '...');

        $aproximadas = [];
        $semCep = [];
        $semCoordenadas = [];
        $erros = [];
        $removidas = [];

        foreach ($delegacias as $d) {
            $rotulo = "{$d->nomeDelegacia} - {$d->cidadeDelegacia}";
            $atualizar = [];

            // ---------- CEP ----------
            $cep = $d->cepDelegacia;
            $cepConsultaOk = true;

            if (empty($cep)) {
                try {
                    $cep = $this->buscarCep($d);
                } catch (\Throwable $e) {
                    $cepConsultaOk = false;
                    $erros[] = "{$rotulo} (CEP): {$e->getMessage()}";
                }
            }

            // ---------- Coordenadas ----------
            $temCoordenadas = $d->latitudedelegacia !== null && $d->longitudedelegacia !== null;
            $coordConsultaOk = true;
            $achado = null;

            if (!$temCoordenadas || $refazer) {
                try {
                    $achado = $this->buscarCoordenadas($d);
                } catch (\Throwable $e) {
                    $coordConsultaOk = false;
                    $erros[] = "{$rotulo} (coordenadas): {$e->getMessage()}";
                }

                if ($achado) {
                    $atualizar['latitudedelegacia'] = $achado['lat'];
                    $atualizar['longitudedelegacia'] = $achado['lon'];

                    if ($achado['nivel'] !== 'exata') {
                        $aproximadas[] = "{$rotulo} ({$achado['nivel']})";
                    }

                    // Fallback de CEP: postcode do Nominatim.
                    if (empty($cep) && $achado['cep']) {
                        $cep = $achado['cep'];
                    }
                }
            }

            if (!empty($cep) && $cep !== $d->cepDelegacia) {
                $atualizar['cepDelegacia'] = $cep;
            }

            $temCoordFinal = $temCoordenadas || $achado !== null;

            // ---------- Remoção (só se as consultas funcionaram) ----------
            $remover = false;

            if ($this->option('remover-sem-cep') && empty($cep) && $cepConsultaOk && $coordConsultaOk) {
                $remover = true;
            }

            if ($this->option('remover-sem-coordenadas') && !$temCoordFinal && $coordConsultaOk) {
                $remover = true;
            }

            if (empty($cep) && $cepConsultaOk && $coordConsultaOk) {
                $semCep[] = $rotulo;
            }

            if (!$temCoordFinal && $coordConsultaOk) {
                $semCoordenadas[] = $rotulo;
            }

            if ($remover) {
                $removidas[] = $rotulo;

                if (!$dryRun) {
                    DB::table(self::TABELA)->where('id', $d->id)->delete();
                }

                $this->line("✗ {$rotulo} removida");
                continue;
            }

            if ($atualizar && !$dryRun) {
                DB::table(self::TABELA)->where('id', $d->id)->update($atualizar + ['updated_at' => now()]);
            }

            $this->line(($atualizar ? '✓' : '·') . " {$rotulo}"
                . ($cep ? " | CEP {$cep}" : ' | sem CEP')
                . ($temCoordFinal ? '' : ' | sem coordenadas'));
        }

        $this->newLine();
        $this->info('Resumo');
        $this->line('  Aproximadas (rua/bairro): ' . count($aproximadas));
        $this->line('  Sem CEP:                  ' . count($semCep));
        $this->line('  Sem coordenadas:          ' . count($semCoordenadas));
        $this->line('  Removidas:                ' . count($removidas));
        $this->line('  Erros de consulta:        ' . count($erros));

        $this->listar('Localizadas só por rua/bairro (confira no mapa)', $aproximadas);
        $this->listar('Ainda sem CEP', $semCep);
        $this->listar('Não localizadas', $semCoordenadas);
        $this->listar('Erros (nada foi apagado por causa deles; rode de novo)', $erros);

        return self::SUCCESS;
    }

    private function listar(string $titulo, array $itens): void
    {
        if (!$itens) {
            return;
        }

        $this->newLine();
        $this->warn($titulo . ':');

        foreach ($itens as $item) {
            $this->line("  - {$item}");
        }
    }

    // =====================================================================
    //  CEP (ViaCEP)
    // =====================================================================

    private function buscarCep(object $d): ?string
    {
        foreach ($this->variantesLogradouro($d->logradouroDelegacia) as $logradouro) {
            $chave = Str::lower("{$d->ufDelegacia}|{$d->cidadeDelegacia}|{$logradouro}");

            if (!array_key_exists($chave, $this->cacheCep)) {
                $url = 'https://viacep.com.br/ws/'
                    . rawurlencode($d->ufDelegacia) . '/'
                    . rawurlencode($d->cidadeDelegacia) . '/'
                    . rawurlencode($logradouro) . '/json/';

                $resposta = Http::timeout(10)->get($url);
                usleep(300_000);

                if ($resposta->status() === 400) {
                    // logradouro curto/inválido para o ViaCEP
                    $this->cacheCep[$chave] = [];
                } elseif (!$resposta->successful()) {
                    throw new RuntimeException("ViaCEP HTTP {$resposta->status()}");
                } else {
                    $json = $resposta->json();
                    $this->cacheCep[$chave] = (is_array($json) && array_is_list($json)) ? $json : [];
                }
            }

            $cep = $this->escolherCep($this->cacheCep[$chave], $d);

            if ($cep) {
                return $cep;
            }
        }

        return null;
    }

    private function escolherCep(array $lista, object $d): ?string
    {
        if (!$lista) {
            return null;
        }

        $numero = ctype_digit((string) $d->numLogradouroDelegacia) ? (int) $d->numLogradouroDelegacia : null;
        $bairro = $d->bairroDelegacia ? Str::lower(Str::ascii($d->bairroDelegacia)) : null;

        $melhor = null;
        $melhorPontos = -1;

        foreach ($lista as $item) {
            $pontos = 0;

            if ($numero !== null && $this->numeroNaFaixa($numero, $item['complemento'] ?? '')) {
                $pontos += 2;
            }

            if ($bairro && Str::lower(Str::ascii($item['bairro'] ?? '')) === $bairro) {
                $pontos += 1;
            }

            if ($pontos > $melhorPontos) {
                $melhorPontos = $pontos;
                $melhor = $item;
            }
        }

        return $melhor['cep'] ?? null;
    }

    /**
     * Interpreta complementos do ViaCEP como "de 301 a 499 - lado ímpar",
     * "até 1000/1001" e "de 1001/1002 ao fim".
     */
    private function numeroNaFaixa(int $n, string $complemento): bool
    {
        $c = Str::lower(Str::ascii($complemento));

        if ($c === '') {
            return false;
        }

        if (str_contains($c, 'lado par') && $n % 2 !== 0) {
            return false;
        }

        if (str_contains($c, 'lado impar') && $n % 2 !== 1) {
            return false;
        }

        if (preg_match('/de\s+(\d+)(?:\/\d+)?\s+a\s+(\d+)/', $c, $m)) {
            return $n >= (int) $m[1] && $n <= (int) $m[2];
        }

        if (preg_match('/de\s+(\d+)(?:\/\d+)?\s+ao\s+fim/', $c, $m)) {
            return $n >= (int) $m[1];
        }

        if (preg_match('/ate\s+(\d+)/', $c, $m)) {
            return $n <= (int) $m[1];
        }

        return false;
    }

    private function variantesLogradouro(string $logradouro): array
    {
        $base = $this->limparLogradouro($logradouro);
        $expandido = str_replace(['Dr. ', 'Dra. '], ['Doutor ', 'Doutora '], $base);

        return array_values(array_unique([
            $base,
            $expandido,
            Str::ascii($base),
            Str::ascii($expandido),
        ]));
    }

    // =====================================================================
    //  Coordenadas (Nominatim) - mesma abordagem do buscarCoord()
    // =====================================================================

    private function buscarCoordenadas(object $d): ?array
    {
        $logradouro = $this->limparLogradouro($d->logradouroDelegacia);
        $numero = ctype_digit((string) $d->numLogradouroDelegacia) ? $d->numLogradouroDelegacia : null;
        $bairro = $d->bairroDelegacia ?: null;
        $cidade = $d->cidadeDelegacia;
        $uf = $d->ufDelegacia;

        $consultas = [];

        if ($numero) {
            if ($bairro) {
                $consultas[] = "{$logradouro}, {$numero}, {$bairro}, {$cidade}, {$uf}, Brasil";
            }

            $consultas[] = "{$logradouro}, {$numero}, {$cidade}, {$uf}, Brasil";
        }

        $consultas[] = "{$logradouro}, {$cidade}, {$uf}, Brasil";

        if ($bairro) {
            $consultas[] = "{$bairro}, {$cidade}, {$uf}, Brasil";
        }

        $base = rtrim(config('services.nominatim.url', 'https://nominatim.openstreetmap.org'), '/');

        foreach ($consultas as $q) {
            if (!array_key_exists($q, $this->cacheCoord)) {
                $resposta = Http::withHeaders([
                    'User-Agent' => 'SOSMulher/1.0',
                ])->timeout(10)->get($base . '/search', [
                    'q' => $q,
                    'format' => 'jsonv2',
                    'limit' => 5,
                    'countrycodes' => 'br',
                    'addressdetails' => 1,
                ]);

                usleep(1_100_000); // política do Nominatim: no máximo 1 requisição por segundo

                if (!$resposta->successful()) {
                    throw new RuntimeException("Nominatim HTTP {$resposta->status()}");
                }

                $this->cacheCoord[$q] = $this->primeiroResultadoEmSp($resposta->json());
            }

            if ($this->cacheCoord[$q] !== null) {
                return $this->cacheCoord[$q];
            }
        }

        return null;
    }

    private function primeiroResultadoEmSp(mixed $resultados): ?array
    {
        if (!is_array($resultados)) {
            return null;
        }

        foreach ($resultados as $item) {
            if (!isset($item['lat'], $item['lon'])) {
                continue;
            }

            $lat = (float) $item['lat'];
            $lon = (float) $item['lon'];

            if ($lat < self::SP_LAT[0] || $lat > self::SP_LAT[1] || $lon < self::SP_LON[0] || $lon > self::SP_LON[1]) {
                continue;
            }

            $endereco = $item['address'] ?? [];

            return [
                'lat' => $lat,
                'lon' => $lon,
                'nivel' => isset($endereco['house_number']) ? 'exata' : (isset($endereco['road']) ? 'rua' : 'bairro'),
                'cep' => $this->normalizarCep($endereco['postcode'] ?? null),
            ];
        }

        return null;
    }

    // =====================================================================
    //  Utilitários
    // =====================================================================

    private function limparLogradouro(string $logradouro): string
    {
        // remove observações como "(esq. Rua Sonho Gaúcho)" e "(Sala B)"
        $limpo = preg_replace('/\s*\(.*?\)/u', '', $logradouro);

        return trim(preg_replace('/\s+/u', ' ', $limpo));
    }

    private function normalizarCep(?string $postcode): ?string
    {
        if (!$postcode) {
            return null;
        }

        $primeiro = explode(';', $postcode)[0];
        $digitos = preg_replace('/\D/', '', $primeiro);

        return strlen($digitos) === 8
            ? substr($digitos, 0, 5) . '-' . substr($digitos, 5)
            : null;
    }
}