@props([
    'type' => 'button',
    'variant' => 'primary', 
    'size' => 'md', 
    'disabled' => false,
    'loading' => false,
    'fullWidth' => false,
    'class' => '',
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'link' => 'btn-link',
    ];
    $sizes = [
        'sm' => 'btn-sm',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
    ];
    $classes = [
        'btn',
        $variants[$variant] ?? 'btn-primary',
        $sizes[$size] ?? 'btn-md',
        $fullWidth ? 'btn-full' : '',
        $disabled || $loading ? 'btn-disabled' : '',
        $class,
    ];
@endphp

<button
    type="{{ $type }}"
    class="{{ implode(' ', array_filter($classes)) }}"
    @if($disabled || $loading) disabled @endif
    @if($loading) aria-busy="true" @endif
>
    @if($loading)
        <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="31.4 31.4" style="animation: spin 1s linear infinite;">
                <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
            </circle>
        </svg>
        <span>{{ $loading }}</span>
    @else
        {{ $slot }}
    @endif
</button>