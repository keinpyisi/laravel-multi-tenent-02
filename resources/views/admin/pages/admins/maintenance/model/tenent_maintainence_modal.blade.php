<div class="container mx-auto p-6 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
    <h1 class="text-2xl font-bold mb-6">管理設定</h1>
    <div class="space-y-6">
        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4">target site</h2>
            <div class="flex space-x-4">
                <!-- resources/views/components/checkbox.blade.php -->

                <label class="flex items-center">
                    <input type="checkbox" name="front_site_modal" value="frontend" class="form-checkbox"
                        checked="checked">
                    <span class="ml-2">フロントサイト</span>
                </label> <!-- resources/views/components/checkbox.blade.php -->

                <label class="flex items-center">
                    <input type="checkbox" name="back_site_modal" value="backend" class="form-checkbox"
                        checked="checked">
                    <span class="ml-2">管理サイト</span>
                </label>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold mb-2">maintenance mode</h3>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="maintenance_0_modal" value="on" class="form-radio">
                        <span class="ml-2">ON</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="maintenance_0_modal" value="scheduled" class="form-radio">
                        <span class="ml-2">設定期間中のみON</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="maintenance_0_modal" value="off" class="form-radio" checked="">
                        <span class="ml-2">OFF</span>
                    </label>
                </div>
            </div>
            <h3 class="text-lg font-semibold mt-4 mb-2">maintenance term</h3>


            <div class="relative">
                <input type="text" name="maintenance_term_modal" id="maintenance_term_modal"
                    class="datepicker form-input pl-9 dark:bg-gray-800 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100 font-medium w-[15.5rem] flatpickr-input"
                    placeholder="Select date range" data-start="2024-11-01 16:00:00" data-end="2024-11-25 15:49:00">
                <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
                    <svg class="fill-current text-gray-400 dark:text-gray-500 ml-3" width="16" height="16"
                        viewBox="0 0 16 16">
                        <path d="M5 4a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H5Z"></path>
                        <path
                            d="M4 0a4 4 0 0 0-4 4v8a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4H4ZM2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Z">
                        </path>
                    </svg>
                </div>
            </div>

            <div>
                <label for="allow_ip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">allow IP
                    address when maintenance mode (Newline
                    separator)</label>
                <textarea name="allow_ip_modal" id="allow_ip" class="form-textarea w-full h-32" rows=""
                    cols=""></textarea>
            </div>
            <div>
                <label class="block
text-sm font-medium text-gray-700 dark:text-gray-300"> Your IP Address: 192.168.33.1</label>
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
            <div>
                <label for="front_main_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">front
                    site maintenance page
                    message</label>
                <textarea name="front_main_message_modal" id="front_main_message" class="form-textarea w-full h-32"
                    rows="" cols=""></textarea>
            </div>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
            <div>
                <label for="back_main_message"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">backend site maintenance page
                    message</label>
                <textarea name="back_main_message_modal" id="back_main_message" class="form-textarea w-full h-32"
                    rows="" cols=""></textarea>
            </div>
        </div>
    </div>
</div>