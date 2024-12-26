@props(['name', 'type' => 'text', 'placeholder' => '', 'description' => '', 'value' => ''])

<div>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    @if ($description)
        <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @endif
</div>
