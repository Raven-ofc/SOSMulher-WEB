{{-- Selo de status da ocorrência: vermelho = aberta, amarelo = em andamento, verde = encerrada. Parâmetro: ocorrencia --}}
<span class="selo-status selo-{{ $ocorrencia->statusClasse() }}">
    <i aria-hidden="true"></i>{{ $ocorrencia->statusRotulo() }}
</span>
