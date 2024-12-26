@props(['name', 'title' => '','options', 'selected' => []])

@foreach($options as $index => $optionGroup)
<div class="mb-4">
    <h3 class="text-lg font-semibold mb-2">{{$title}}</h3>
    <div class="flex space-x-4">
        @foreach($optionGroup as $value => $label)
        <label class="flex items-center">
            <input type="radio" name="{{ $name }}_{{ $index }}" value="{{ $value }}" class="form-radio"
                {{ (isset($selected[$index]) && $selected[$index] == $value) ? 'checked' : '' }}>
            <span class="ml-2">{{ $label }}</span>
        </label>
        @endforeach
    </div>
</div>
@endforeach