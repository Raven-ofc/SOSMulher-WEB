@props([
    'variant' => 'default', // default, form
    'class' => '',
    'padding' => 'md', // none, sm, md, lg
])

@php
    $variants = [
        'default' => 'card',
        'form' => 'card card-form',
    ];
    $paddings = [
        'none' => '',
        'sm' => 'card-p-sm',
        'md' => 'card-p-md',
        'lg' => 'card-p-lg',
    ];
    $classes = [
        $variants[$variant] ?? 'card',
        $paddings[$padding] ?? 'card-p-md',
        $class,
    ];
@endphp

<div class="{{ implode(' ', array_filter($classes)) }}">
    {{ $slot }}
</div>