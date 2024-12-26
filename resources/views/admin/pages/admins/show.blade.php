<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 bg-gray-50">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                クライアント管理
            </h2>
        </div>

        <!-- Tab Section -->
        <div x-data="{ activeTab: 0 }" class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 bg-white p-4 rounded-lg shadow-sm">
                {{ $tenant->client_name }} ( {{ $tenant->domain }})
            </h2>

            <!-- Enhanced Tab Navigation -->
            <div class="flex space-x-4 border-b border-gray-200 bg-white rounded-t-lg px-4">
                <button 
                    :class="{
                        'text-blue-600 border-b-2 border-blue-600 font-semibold': activeTab === 0,
                        'text-gray-600 hover:text-blue-500': activeTab !== 0
                    }"
                    @click="activeTab = 0" 
                    class="py-4 px-6 text-sm font-medium focus:outline-none transition duration-200">
                    詳細
                </button>
                <button 
                    :class="{
                        'text-blue-600 border-b-2 border-blue-600 font-semibold': activeTab === 1,
                        'text-gray-600 hover:text-blue-500': activeTab !== 1
                    }"
                    @click="activeTab = 1" 
                    class="py-4 px-6 text-sm font-medium focus:outline-none transition duration-200">
                    使用状況
                </button>
                <button 
                    :class="{
                        'text-blue-600 border-b-2 border-blue-600 font-semibold': activeTab === 3,
                        'text-gray-600 hover:text-blue-500': activeTab !== 3
                    }"
                    @click="activeTab = 3"
                    class="user_setting_btn py-4 px-6 text-sm font-medium focus:outline-none transition duration-200">
                    ユーザー設定
                </button>
                <button 
                    :class="{
                        'text-blue-600 border-b-2 border-blue-600 font-semibold': activeTab === 2,
                        'text-gray-600 hover:text-blue-500': activeTab !== 2
                    }"
                    @click="activeTab = 2" 
                    class="py-4 px-6 text-sm font-medium focus:outline-none transition duration-200">
                    認証情報
                </button>
            </div>

            <!-- Tab Content Container -->
            <div class="bg-white rounded-b-lg shadow-sm">
                <div class="mt-4">
                    <!-- Details Tab Content -->
<div x-show="activeTab === 0" class="p-6">
    <form action="{{ route('admin.tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Account Name -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">アカウント名*</label>
                <div class="bg-gray-50 px-4 py-2.5 rounded-md border border-gray-200">
                    {{ $tenant->account_name }}
                </div>
                <input type="hidden" name="account_name" value="{{ $tenant->account_name }}">
            </div>

            <!-- Client Name -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">クライアント名*</label>
                <input type="text" 
                       name="client_name" 
                       value="{{ $tenant->client_name }}" 
                       placeholder="例:アスコン商店"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                @error('client_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kana -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">フリガナ*</label>
                <input type="text" 
                       name="kana" 
                       value="{{ $tenant->kana }}" 
                       placeholder="例:アスコンショウテン"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                @error('kana')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Person in Charge -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">責任者名</label>
                <input type="text" 
                       name="person_in_charge" 
                       value="{{ $tenant->person_in_charge }}" 
                       placeholder="刹那恵"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- Logo Upload -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">ロゴ*</label>
                <div class="space-y-2">
                    <input type="file" 
                           name="logo" 
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100">
                    @php
                        $logoPath = $tenant->logo;
                        $domain = explode('/', $logoPath)[0];
                        $file = basename($logoPath);
                    @endphp
                    <div class="mt-2 border rounded-lg p-2 bg-gray-50 w-fit">
                        <img src="{{ route('tenant.logo', ['domain' => $domain, 'file' => $file]) }}"
                             alt='Logo' 
                             class='h-20 w-auto object-contain'>
                    </div>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact Information -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">お問い合わせメールアドレス</label>
                <input type="email" 
                       name="support_mail" 
                       value="{{ $tenant->support_mail }}" 
                       placeholder="例:support@ascon.co.jp"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                @error('support_mail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <!-- Email -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input type="email" 
                       name="e_mail" 
                       value="{{ $tenant->e_mail }}" 
                       placeholder="例:info@ascon.co.jp"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                @error('e_mail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone and FAX -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">電話番号</label>
                <input type="tel" 
                       name="tel" 
                       value="{{ $tenant->tel }}" 
                       placeholder="01-123-3345"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">FAX番号</label>
                <input type="tel" 
                       name="fax_number" 
                       value="{{ $tenant->fax_number }}" 
                       placeholder="01-123-3345"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- Website -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">ホームページ</label>
                <input type="url" 
                       name="url" 
                       value="{{ $tenant->url }}" 
                       placeholder="https://www.example.com"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- Address Information -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">郵便番号</label>
                <input type="text" 
                       name="post_code" 
                       value="{{ $tenant->post_code }}" 
                       placeholder="134-0084"
                       class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">住所</label>
                <textarea name="address" 
                          rows="3" 
                          class="form-textarea rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                          placeholder="住所を入力してください">{{ $tenant->address }}</textarea>
            </div>

            <!-- Notes -->
            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-1">備考</label>
                <textarea name="note" 
                          rows="3" 
                          class="form-textarea rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                          placeholder="備考を入力してください">{{ $tenant->note }}</textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                           transition duration-200 ease-in-out">
                変更
            </button>
        </div>
    </form>
</div>
<!-- Usage Statistics Tab Content -->
<div x-show="activeTab === 1" class="p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Overall Usage Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                全体
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-lg">
                    <span class="text-gray-600">全体サイズ:</span>
                    <span class="font-medium text-gray-800">{{ $all_usage['total_size'] }}</span>
                </div>
                <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-lg">
                    <span class="text-gray-600">空き容量:</span>
                    <span class="font-medium text-gray-800">{{ $all_usage['free_space'] }}</span>
                </div>
                <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-lg">
                    <span class="text-gray-600">使用容量:</span>
                    <span class="font-medium text-gray-800">{{ $all_usage['used_space'] }}</span>
                </div>
                <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-lg">
                    <span class="text-gray-600">使用率:</span>
                    <span class="font-medium text-gray-800">{{ $all_usage['usage_rate'] }}</span>
                </div>
            </div>
        </div>

        <!-- Client Usage Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                企画添付ファイル
            </h3>
            <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-lg">
                <span class="text-gray-600">使用容量:</span>
                <span class="font-medium text-gray-800">{{ $client_usage['total_size'] }}</span>
            </div>
        </div>

        <!-- Usage Chart (Optional) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition duration-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                使用率グラフ
            </h3>
            <div class="h-48 bg-gray-50 rounded-lg flex items-center justify-center">
                <!-- Add your chart component here -->
                <span class="text-gray-400">グラフ表示エリア</span>
            </div>
        </div>
    </div>
</div>
<!-- Authentication Tab Content -->
<div x-show="activeTab === 2" class="p-6">
    <div class="max-w-3xl mx-auto">
        <!-- Warning Messages -->
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" 
                              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" 
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="text-sm text-red-600 space-y-1">
                        <p>クライアント作成後、または、認証リセット後の同一セッション中のみ</p>
                        <p>パスワードの表示、または、認証情報を一度のみダウンロードできます。</p>
                        <p>パスワードが分からなくなった場合は、リセットしてください。</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Authentication Info Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                ユーザー情報
            </h2>
            
            <form action="{{ route('admin.tenants.reset', $tenant->domain) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Username -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-medium text-gray-700">ユーザー名</label>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="text-gray-600">現在のユーザー名:</span>
                            <span class="font-semibold text-gray-800">{{ $tenant->domain }}</span>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-medium text-gray-700">パスワード</label>
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="text-gray-600">現在のパスワード:</span>
                            <span class="font-semibold text-gray-800">
                                @if (isset(session('success')['basic_pass']) && session('success')['basic_pass'])
                                    {{ session('success')['basic_pass'] }}
                                @else
                                    ********
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="button"
                                id="basic_reset_btn"
                                data-id="{{ $tenant->domain }}"
                                class="basic_reset_btn inline-flex items-center px-6 py-2.5 bg-red-600 text-white rounded-md 
                                       hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 
                                       transition duration-200 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Basic認証リセット
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- User Settings Tab Content -->
<div x-show="activeTab === 3" class="p-6">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    アカウント設定
                </h2>
                <div class="space-x-3">
                    <!-- Add User Button -->
                    <button id="addUserBtn" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md 
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 
                                   focus:ring-offset-2 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        新規
                    </button>
                    
                    <!-- Delete User Button -->
                    <button id="delUserBtn"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md 
                                   hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 
                                   focus:ring-offset-2 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        削除
                    </button>
                </div>
            </div>

            <!-- Search Box -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" 
                           id="user_search" 
                           placeholder="検索..." 
                           class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-gray-50 border border-gray-300 
                                  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="selectAll"
                                           class="rounded border-gray-300 text-blue-600 
                                                  focus:ring-blue-500 h-4 w-4 cursor-pointer">
                                </div>
                            </th>
                            <th scope="col" 
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" 
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                LoginId
                            </th>
                            <th scope="col" 
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User Name
                            </th>
                            <th scope="col" 
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Updated Date
                            </th>
                            <th scope="col" 
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Edited User
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 tenent_user_table">
                        <!-- Table rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <nav class="flex items-center justify-center" id="pagination-controls">
                    <!-- Pagination controls will be dynamically inserted here -->
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Closing divs for the main container -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
