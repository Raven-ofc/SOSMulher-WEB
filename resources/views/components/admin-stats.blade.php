@props(['monitoring' => false])
<div class="admin-stats">
    @foreach (($monitoring ? [['monitor', 'blue', 'Aparelhos ativos'], ['chip', 'violet', 'Tornozeleiras online'], ['warning', 'red', 'Ocorrências hoje']] : [['warning', 'red', 'Ocorrências hoje'], ['chip', 'violet', 'Tornozeleiras online'], ['monitor', 'blue', 'Aparelhos ativos'], ['headset', 'purple', 'Oficiais ativos']]) as [$icon, $color, $label])
    <div class="admin-stat">
        <span class="stat-icon {{ $color }}"><x-admin-icon :name="$icon" /></span>
        <div><strong aria-label="Não disponível">—</strong><span>{{ $label }}</span></div>
    </div>
    @endforeach
</div>
