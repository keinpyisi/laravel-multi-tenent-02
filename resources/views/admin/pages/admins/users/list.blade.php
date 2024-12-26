<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    アカウント設定
                </h2>
                <div class="space-x-3">
                    <x-admin::button :action="[
                        'id' => 'addBtn',
                        'label' => '新規',
                        'type' => 'button',
                        'class' => 'px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium
                                  hover:bg-blue-700 transition-colors duration-200
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                  shadow-sm',
                    ]">
                    </x-admin::button>
                    <x-admin::button :action="[
                        'id' => 'delBtn',
                        'label' => '削除',
                        'type' => 'button',
                        'class' => 'px-5 py-2.5 bg-red-600 text-white rounded-lg font-medium
                                  hover:bg-red-700 transition-colors duration-200
                                  focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                                  shadow-sm',
                    ]">
                    </x-admin::button>
                </div>
            </div>

            <!-- Search Box -->
            <div class="mb-6">
                <div class="relative">
                    <input 
                        type="text" 
                        name="searchInput" 
                        id="searchInput" 
                        placeholder="検索。。。"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                               transition-colors duration-200 bg-white
                               text-gray-700 placeholder-gray-400"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3">
                                <input 
                                    type="checkbox" 
                                    id="selectAll"
                                    class="rounded border-gray-300 text-blue-600 
                                           focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                                           transition-colors duration-200"
                                >
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                LoginId
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                User Name
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Updated Date
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Edited User
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 admin_table">
                        <!-- Table rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <nav class="flex space-x-2" id="pagination-controls">
                    <!-- Pagination controls will be dynamically added here -->
                </nav>
            </div>
        </div>
    </div>
</x-app-layout>