<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 bg-gray-50">
        <!-- Header Section -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Maintenance Mode Setting
            </h2>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <p class="text-red-700 font-medium">
                    状態は全体設定優先。全体でメンテナンスモードがOFFのときに、個別設定を反映します。
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">管理設定</h2>
            
            <form action="{{ route('admin.maitenance.store') }}" id="create-tenant" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-8">
                    <!-- Target Site Section -->
                    < class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Target Site</h3>
                        
                        <div class="flex space-x-6 mb-6">
                            <x-admin::checkbox 
                                name="front_site" 
                                label="フロントサイト" 
                                value="frontend"
                                :checked="isset($json_data['front_site']) && $json_data['front_site']"
                                class="text-blue-600 focus:ring-blue-500"
                            />
                            <x-admin::checkbox 
                                name="back_site" 
                                label="管理サイト" 
                                value="backend"
                                :checked="isset($json_data['back_site']) && $json_data['back_site']"
                                class="text-blue-600 focus:ring-blue-500"
                            />
                        </div>
                        @php
                        $maintenanceOptions = [[
                        'on' => 'ON',
                        'scheduled' => '設定期間中のみON',
                        'off' => 'OFF'
                        ],];

                        $selectedOptions = isset($json_data['maintenance_0']) ? [$json_data['maintenance_0']] :
                        ['off'];
                        @endphp
                        <!-- Maintenance Mode Options -->
                        <x-admin::radio_btn 
                            name="maintenance" 
                            title="Maintenance Mode" 
                            :options="$maintenanceOptions"
                            :selected="$selectedOptions" 
                            class="space-y-2"
                        />

                        <x-admin::datepicker name="maintenance_term" />

                        @error('maintenance_term')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        

                        <!-- Allow IP Address -->
                        <div class="mt-6">
                            <x-admin::textareas 
                                label="Allow IP Address During Maintenance Mode (Newline separator)" 
                                name="allow_ip" 
                                id="allow_ip"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                :value="isset($json_data['allow_ip']) ? implode(PHP_EOL, $json_data['allow_ip']) : ''"
                            />
                            <p class="mt-2 text-sm text-gray-600">Your IP Address: {{request()->ip()}}</p>
                        </div>
                    </div>

                    <!-- Message Sections -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <x-admin::textareas 
                                label="Front Site Maintenance Page Message" 
                                name="front_main_message"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500"
                                :value="$json_data['front_main_message'] ?? ''"
                            />
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <x-admin::textareas 
                                label="Backend Site Maintenance Page Message" 
                                name="back_main_message"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500"
                                :value="$json_data['back_main_message'] ?? ''"
                            />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <x-admin::button 
                            :action="[
                                'label' => '保存',
                                'type' => 'submit',
                                'class' => 'px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg
                                          hover:bg-blue-700 transition-colors duration-200
                                          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
                            ]">
                        </x-admin::button>
                    </div>
                </div>
            </form>

            <!-- Table Section -->
            <div class="mt-12">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">管理設定</h2>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Client Name
                                </th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    対象
                                </th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    期間
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 tenant_table">
                            <!-- Table content will be dynamically added -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center" id="pagination-controls"></div>
        </div>
    </div>
</x-app-layout>