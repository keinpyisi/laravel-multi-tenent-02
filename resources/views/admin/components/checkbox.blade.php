<!-- resources/views/components/checkbox.blade.php -->
@props(['name', 'value' => '', 'label'])

<label class="flex items-center">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" class="form-checkbox" {{ $attributes }}>
    <span class="ml-2">{{ $label }}</span>
</label>