@props([
    'headers' => [],
    'rows' => [],
])

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-700">
        <thead class="text-xs uppercase bg-gray-100">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="px-6 py-3 font-semibold text-gray-800">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr class="bg-white border-b hover:bg-gray-50 transition-colors duration-200">
                    @foreach ($row as $key => $value)
                        @if ($loop->first)
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $value }}
                            </th>
                        @elseif(is_array($value))
                            {{-- If it's an array of actions --}}
                            <td class="px-6 py-4">
                                <div class="flex gap-2 items-center">
                                    @foreach ($value as $action)
                                        @php
                                            $target = isset($action['target']) && $action['target'] === 'new' 
                                                ? 'target="_blank"' 
                                                : '';
                                        @endphp
                                        @if (is_array($action) && isset($action['label'], $action['class']))
                                            @if (isset($action['type']) && $action['type'] === 'button')
                                                @if (isset($action['url']) && isset($action['method']))
                                                    {{-- Button Action --}}
                                                    <form action="{{ $action['url'] }}"
                                                        method="{{ in_array($action['method'], ['DELETE', 'PUT']) ? 'POST' : $action['method'] }}"
                                                        class="inline" {{ $target }}>
                                                        @csrf
                                                        @if (in_array($action['method'], ['DELETE', 'PUT']))
                                                            @method($action['method'])
                                                        @endif
                                                        <button type="submit" 
                                                            class="px-4 py-2 text-sm font-medium rounded-md shadow-sm 
                                                            transition-all duration-200 ease-in-out
                                                            {{ str_contains($action['class'], 'delete') 
                                                                ? 'bg-red-500 text-white hover:bg-red-600' 
                                                                : 'bg-blue-500 text-white hover:bg-blue-600' }}">
                                                            {{ $action['label'] }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" 
                                                        class="px-4 py-2 text-sm font-medium rounded-md shadow-sm 
                                                        transition-all duration-200 ease-in-out
                                                        {{ str_contains($action['class'], 'delete') 
                                                            ? 'bg-red-500 text-white hover:bg-red-600' 
                                                            : 'bg-blue-500 text-white hover:bg-blue-600' }}">
                                                        {{ $action['label'] }}
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ $action['url'] }}" 
                                                   id="{{ $action['id'] }}"
                                                   class="px-4 py-2 text-sm font-medium rounded-md shadow-sm 
                                                   transition-all duration-200 ease-in-out
                                                   {{ str_contains($action['class'], 'delete') 
                                                       ? 'bg-red-500 text-white hover:bg-red-600' 
                                                       : 'bg-blue-500 text-white hover:bg-blue-600' }}"
                                                   {{ $target }}>
                                                    {{ $action['label'] }}
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                        @else
                            <td class="px-6 py-4 text-gray-700">
                                {!! $value !!}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>