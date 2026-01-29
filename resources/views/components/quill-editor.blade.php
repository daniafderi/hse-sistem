@props([
    'name', // nama input form
    'label' => 'Konten', // label bawaan
    'value' => '', // isi default (opsional)
    'placeholder' => 'Tulis sesuatu di sini...' // placeholder bawaan
])
    @if ($label)
        <x-input-label for="{{ $name }}">{{ $label }}</x-input-label>
    @endif
<div class="">
    <div id="editor-{{ $name }}" class="border rounded-md quill-editor"></div>
    <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}">
</div>
