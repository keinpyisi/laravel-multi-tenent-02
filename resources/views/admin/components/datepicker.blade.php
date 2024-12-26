@props(['name', 'start' => '', 'end' => ''])

<div class="relative">
    <input 
        type="text" 
        name="{{ $name }}" 
        id="{{ $name }}-datepicker"
        class="
            w-full
            px-4
            py-2.5
            pl-10
            rounded-lg
            border
            border-gray-300
            bg-white
            text-gray-700
            placeholder-gray-400
            font-medium
            transition-colors
            duration-200
            focus:border-blue-500
            focus:ring-2
            focus:ring-blue-200
            focus:outline-none
            hover:border-gray-400
        "
        placeholder="Select date range"
        data-start="{{ $start }}"
        data-end="{{ $end }}"
    />
    
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg 
            class="w-5 h-5 text-gray-400 transition-colors duration-200" 
            viewBox="0 0 20 20" 
            fill="currentColor"
        >
            <path 
                fill-rule="evenodd" 
                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" 
                clip-rule="evenodd"
            />
        </svg>
    </div>
</div>
