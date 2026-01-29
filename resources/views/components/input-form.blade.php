@props(['type' => 'text'])

<input {{ $attributes->merge(['class' => 'w-full border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-lg shadow-sm']) }} type="{{ $type }}">
