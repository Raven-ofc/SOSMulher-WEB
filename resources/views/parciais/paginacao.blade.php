@if ($paginator->hasPages())
    <nav class="paginacao" aria-label="Paginação">
        <p>
            Exibindo {{ $paginator->firstItem() ?? 0 }} a {{ $paginator->lastItem() ?? 0 }}
            de {{ $paginator->total() }} registros
        </p>
        <ul class="paginacao-lista">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="paginacao-link paginacao-desabilitada" aria-disabled="true">Anterior</span>
                @else
                    <a class="paginacao-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
                @endif
            </li>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="paginacao-link" aria-hidden="true">{{ $element }}</span></li>
                @else
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="paginacao-link paginacao-atual" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="paginacao-link" href="{{ $url }}" aria-label="Página {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach
            <li>
                @if ($paginator->hasMorePages())
                    <a class="paginacao-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Próxima</a>
                @else
                    <span class="paginacao-link paginacao-desabilitada" aria-disabled="true">Próxima</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
