@props([
    'align' => 'right',
])

<div class="relative inline-flex" x-data="{ open: false }">
    <button class="inline-flex justify-center items-center group" aria-haspopup="true" @click.prevent="open = !open"
        :aria-expanded="open">
        <div class="flex items-center truncate">
            <span class="truncate ml-2 text-sm font-medium text-gray-600 dark:text-gray-100 group-hover:text-gray-800 dark:group-hover:text-white"></span>
            <svg class="svg-inline--fa fa-user" aria-hidden="true" focusable="false" data-prefix="far" data-icon="user"
                role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                <path fill="currentColor"
                    d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464l349.5 0c-8.9-63.3-63.3-112-129-112l-91.4 0c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304l91.4 0C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7L29.7 512C13.3 512 0 498.7 0 482.3z">
                </path>
            </svg>
            <span class="d-none d-md-inline">開発者</span> 
            <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500" viewBox="0 0 12 12">
                <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
            </svg>
        </div>
    </button>
    <div class="origin-top-right z-10 absolute top-full min-w-44 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1 {{ $align === 'right' ? 'right-0' : 'left-0' }}"
        @click.away="open = false" @keydown.escape.window="open = false" x-show="open" x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-cloak>
        <ul>
            <li>
                <form method="POST" action="{{ route('admin.users.logout') }}" x-data>
                    @csrf
                    <a class="font-medium text-sm text-violet-500 hover:text-violet-600 dark:hover:text-violet-400 flex items-center py-1 px-3"
                        href="" @click.prevent="$root.submit();">
                        {{ __('Sign Out') }}
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>
