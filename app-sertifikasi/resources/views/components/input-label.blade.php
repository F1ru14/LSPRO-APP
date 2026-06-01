@props(['value'])

<label {{ $attributes->merge(['class' => 'custom-input-label']) }}>
    {{ $value ?? $slot }}
</label>
