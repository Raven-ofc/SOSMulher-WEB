@extends('estruturas.administracao',['pageTitle'=>'Usuárias/Vítimas','active'=>'admin.vitimas.index'])
@section('admin-content')
<header class="admin-heading">
    <h1>Vítimas</h1>
</header>
<a class="admin-back" href="{{ route('admin.vitimas.index') }}">‹ voltar</a>
<section class="admin-panel victim-detail">
    <div class="victim-profile">
        <div class="victim-profile-heading" style="display: flex; align-items: center; justify-content: flex-start; gap: 20px; margin-bottom: 24px;">
            @php
                $img = $vitima->imagemVitima;
                $fotoUrl = null;
                if ($img) {
                    $fotoUrl = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
                }
            @endphp

            @if($fotoUrl)
                <img src="{{ $fotoUrl }}" alt="{{ $vitima->nomeVitima }}" style="width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 3px solid #bfaeff; flex-shrink: 0;">
            @else
                <div style="width: 85px; height: 85px; border-radius: 50%; background: #e9d9fa; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #9b67c6; font-weight: 600; flex-shrink: 0; border: 3px solid #bfaeff;">
                    {{ strtoupper(substr($vitima->nomeVitima, 0, 1)) }}
                </div>
            @endif

            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <h2 style="margin: 0; font-size: 1.6rem; color: #38284b;">{{ $vitima->nomeVitima }}</h2>
                <span class="status-neutral">{{ ucfirst($vitima->statusVitima) }}</span>
            </div>
        </div>

        <dl class="victim-info">
            <div>
                <dt>CPF:</dt>
                <dd>{{ $vitima->cpfVitima }}</dd>
            </div>
            <div>
                <dt>Nascimento:</dt>
                <dd>{{ \Carbon\Carbon::parse($vitima->dataNascimentoVitima)->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt>Mãe:</dt>
                <dd>{{ $vitima->nomeMaeVitima ?? '—' }}</dd>
            </div>
            <div>
                <dt>Pai:</dt>
                <dd>{{ $vitima->nomePaiVitima ?? '—' }}</dd>
            </div>
            <div>
                <dt>Telefone:</dt>
                <dd>{{ ($vitima->telefones()->first()?->numeroTelefoneVitima ?? '—') }}</dd>
            </div>
            <div>
                <dt>E-mail:</dt>
                <dd>{{ $vitima->emailVitima }}</dd>
            </div>
            <div>
                <dt>Endereço:</dt>
                <dd>
                    @if($vitima->endereco)
                        {{ $vitima->endereco->logradouroVitima }}, {{ $vitima->endereco->numLogradouroVitima }}
                        @if($vitima->endereco->complementoVitima) - {{ $vitima->endereco->complementoVitima }} @endif
                        <br>{{ $vitima->endereco->bairroVitima }} — {{ $vitima->endereco->cidadeVitima }}/{{ $vitima->endereco->ufVitima }}
                        <br>CEP: {{ $vitima->endereco->cepVitima }}
                    @else
                        —
                    @endif
                </dd>
            </div>
        </dl>
        <div class="victim-profile-actions">
            <a class="admin-action secondary" href="{{ route('admin.vitimas.editar',$vitima->id) }}">editar info</a>
            <form method="POST" action="{{ route('admin.vitimas.status',$vitima->id) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $vitima->statusVitima==='ativo'?'inativo':'ativo' }}">
                <button class="admin-action">{{ $vitima->statusVitima==='ativo'?'Inativar':'Ativar' }}</button>
            </form>
        </div>
    </div>
    <div class="victim-related">
        <section>
            <h2>Ocorrências</h2>
            @forelse($occurrences as $item)
                <p class="victim-related-empty">
                    <a href="{{ route('admin.ocorrencias.visualizar',$item->id) }}">
                        {{ $item->tipoOcorrencia }}
                        —
                        {{ $item->dataOcorrencia }}
                    </a>
                </p>
            @empty
                <p class="victim-related-empty">Nenhuma ocorrência registrada.</p>
            @endforelse
            {{ $occurrences->links() }}
        </section>
    </div>
</section>
@endsection