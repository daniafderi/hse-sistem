@props(['value', 'required' => 'false'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 mb-1']) }}>
    {{ $value ?? $slot }}
    @if ($required != 'false')
        <sup class="text-red-500">*</sup>
    @endif
</label>
