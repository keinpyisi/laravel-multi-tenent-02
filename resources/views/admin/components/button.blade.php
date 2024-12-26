<button id="{{ $action['id'] ?? '' }}" type="{{ $action['which_type'] ?? 'submit' }}"
    data-id="{{ $action['data-id'] ?? '' }}" @if (isset($action['form'])) form="{{ $action['form'] }}" @endif
    class="{{ $action['class'] ?? 'btn bg-gray-900 text-gray-100 hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-800 dark:hover:bg-white whitespace-nowrap' }}">
    {{-- Check if an icon exists and add <i> tag --}}
    @if (isset($action['icon']))
        <i class="{{ $action['icon'] }}"></i>
    @endif

    {{ $action['label'] ?? $slot }}
</button>
