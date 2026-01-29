@props(['rel' => 'nopeener noreferrer', 'link' => '#', 'label' => 'Link Title', 'color' => 'indigo'])

@php
    $colors = [
        'indigo' => 'bg-indigo-600 hover:bg-indigo-700',
        'blue' => 'bg-blue-600 hover:bg-blue-700',
        'red' => 'bg-red-700 hover:bg-red-600',
        'none' => 'bg-none'
    ];

    $defaultClass = 'rounded-lg flex gap-1 text-white text-sm font-medium px-3 py-1.5 items-center ' . ($colors[$color] ?? $colors['blue']);
@endphp

<a {{ $attributes->merge(['class' => $defaultClass]) }} href="{{ $link }}" rel="{{ $rel }}">
    {{ $slot }}
    <span>
        {{ $label }}
    </span>
</a>