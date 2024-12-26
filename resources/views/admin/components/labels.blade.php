@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'description' => '', 'value' => '' , 'class'=>"block
text-sm font-medium text-gray-700 dark:text-gray-300"])

<div>
    <label class="{{$class}}">{{ $label }}</label>
    @if ($description)
    <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
    @endif
</div>