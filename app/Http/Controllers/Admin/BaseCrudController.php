<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

abstract class BaseCrudController extends \App\Http\Controllers\Controller
{
    protected string $modelo;
    protected string $visualizacaoBase;
    protected string $rotaBase;
    protected array $carregarRelacionamentos = [];

    public function index(Request $request)
    {
        $filtros = $request->validate($this->regrasFiltros());
        $query = $this->modelo::query()->with($this->carregarRelacionamentos);

        if ($busca = $filtros['busca'] ?? null) {
            $query->where(function ($q) use ($busca) {
                $this->aplicarBusca($q, $busca);
            });
        }

        if ($status = $filtros['status'] ?? null) {
            $query->where($this->colunaStatus(), $status);
        }

        $ordenacao = $this->ordenacaoPadrao();
        return view("{$this->visualizacaoBase}.lista", [
            $this->variavelLista() => $query->orderBy($ordenacao[0], $ordenacao[1])->paginate(15)->withQueryString()
        ]);
    }

    public function create()
    {
        return $this->criar();
    }

    public function store(Request $request)
    {
        return $this->salvar($request);
    }

    public function show(int $id)
    {
        return $this->visualizar($id);
    }

    public function edit(int $id)
    {
        return $this->editar($id);
    }

    public function update(Request $request, int $id)
    {
        return $this->atualizar($request, $id);
    }

    public function destroy(int $id)
    {
        DB::transaction(function () use ($id) {
            $registro = $this->modelo::lockForUpdate()->findOrFail($id);
            $registro->delete();
        });

        return redirect()->route("{$this->rotaBase}.index")
            ->with('status', 'Registro removido.');
    }

    public function criar()
    {
        return view("{$this->visualizacaoBase}.formulario", [
            $this->variavelSingular() => null,
            ...$this->dadosExtrasCriacao()
        ]);
    }

    public function salvar(Request $request)
    {
        return $this->persistir($request);
    }

    public function visualizar(int $id)
    {
        $registro = $this->modelo::with($this->carregarRelacionamentos)->findOrFail($id);
        return view("{$this->visualizacaoBase}.detalhes", [
            $this->variavelSingular() => $registro,
            ...$this->dadosExtrasVisualizacao($registro)
        ]);
    }

    public function editar(int $id)
    {
        return view("{$this->visualizacaoBase}.formulario", [
            $this->variavelSingular() => $this->modelo::findOrFail($id),
            ...$this->dadosExtrasEdicao()
        ]);
    }

    public function atualizar(Request $request, int $id)
    {
        return $this->persistir($request, $id);
    }

    public function alterarStatus(Request $request, int $id)
    {
        $dados = $request->validate([
            'status' => ['required', 'string', Rule::in($this->statusPermitidos())]
        ]);

        DB::transaction(function () use ($id, $dados) {
            $registro = $this->modelo::lockForUpdate()->findOrFail($id);

            if ($dados['status'] === $this->statusInativo() && $this->temVinculosAtivos($registro)) {
                throw ValidationException::withMessages([
                    'status' => 'Há vínculos ativos. Resolva antes de inativar.'
                ]);
            }

            $registro->{$this->colunaStatus()} = $dados['status'];
            $registro->save();
        });

        return back()->with('status', 'Status atualizado.');
    }

    protected function persistir(Request $request, ?int $id = null)
    {
        $registro = $id ? $this->modelo::findOrFail($id) : new $this->modelo;
        $regras = $id ? $this->regrasAtualizacao($id) : $this->regrasCriacao();
        // 1) normaliza o que foi digitado (CPF, telefone...), 2) valida, 3) executa efeitos (upload de foto etc.)
        $dados = $this->normalizarEntrada($request->all(), $id);
        $request->merge($dados);
        $dados = $request->validate($regras);
        $dados = $this->processarDados($request, $dados, $id);

        $registro->forceFill($this->mapearDados($dados));
        if (!$id) {
            $registro->{$this->colunaStatus()} = $this->statusInicial();
        }
        $registro->save();
        $this->aposSalvar($registro, $dados, $id);

        return redirect()->route("{$this->rotaBase}.visualizar", $registro->id)
            ->with('status', 'Cadastro salvo.');
    }

    protected function aplicarBusca($query, string $busca): void
    {
        $query->where(function ($q) use ($busca) {
            foreach ($this->colunasBusca() as $coluna) {
                $q->orWhere($coluna, 'like', "%{$busca}%");
            }
        });
    }

    protected function temVinculosAtivos(Model $registro): bool
    {
        return false;
    }

    protected function aposSalvar(Model $registro, array $dados, ?int $id): void {}

    abstract protected function colunaStatus(): string;
    abstract protected function colunasBusca(): array;
    abstract protected function mapearDados(array $dados): array;
    abstract protected function variavelLista(): string;
    abstract protected function variavelSingular(): string;

    protected function regrasFiltros(): array
    {
        return [
            'busca' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:ativo,inativo'],
        ];
    }

    protected function regrasCriacao(): array
    {
        return [];
    }

    protected function regrasAtualizacao(int $id = null): array
    {
        return [];
    }

    protected function statusInicial(): string
    {
        return 'ativo';
    }

    protected function ordenacaoPadrao(): array
    {
        return ['id', 'asc'];
    }
    protected function dadosExtrasCriacao(): array
    {
        return [];
    }
    protected function dadosExtrasEdicao(): array
    {
        return [];
    }
    protected function dadosExtrasVisualizacao(Model $registro): array
    {
        return [];
    }
    /** Formata os dados antes da validação (sem efeitos colaterais). */
    protected function normalizarEntrada(array $dados, ?int $id = null): array
    {
        return $dados;
    }

    /** Roda depois da validação; é aqui que se grava arquivo, gera senha etc. */
    protected function processarDados(Request $request, array $dados, ?int $id = null): array
    {
        return $dados;
    }

    protected function statusPermitidos(): array
    {
        return ['ativo', 'inativo'];
    }

    protected function statusInativo(): string
    {
        return 'inativo';
    }
}
