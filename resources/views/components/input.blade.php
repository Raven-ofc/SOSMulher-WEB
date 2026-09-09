@props([
    'type' => 'text',
    'name',
    'id',
    'label',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'autocomplete' => 'off',
    'autofocus' => false,
])

@php
    $hasError = !is_null($error);
    $inputId = $id ?? $name;
    $errorId = $hasError ? "{$inputId}-error" : null;
@endphp

<div class="form-field">
    @if($label)
        <label for="{{ $inputId }}" class="form-label">{{ $label }}</label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="{{ $type === 'password' ? '' : old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="form-input {{ $hasError ? 'has-error' : '' }}"
        @required($required)
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if($autofocus) autofocus @endif
        aria-invalid="{{ $hasError ? 'true' : 'false' }}"
        @if($hasError) aria-describedby="{{ $errorId }}" @endif
    >

    @if($hasError)
        <span id="{{ $errorId }}" class="form-error">{{ $error }}</span>
    @endif
</div>