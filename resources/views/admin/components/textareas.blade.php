@props([
'label',
'name',
'type' => 'text',
'placeholder' => '',
'description' => '',
'value' => '',
'rows' => '',
'cols' => '',
'class'=>"border border-gray-300 rounded-md p-2",
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}" class="{{ $class }}" @if(isset($rows)) rows="{{ $rows }}" @endif
        @if(isset($cols)) cols="{{ $cols }}" @endif>{!! $value !!}</textarea>
    @if ($description)
    <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @endif
</div>