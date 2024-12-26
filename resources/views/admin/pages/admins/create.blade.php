<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-800">
                新規クライアント登録
            </h2>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('admin.tenants.store') }}" id="create-tenant" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="px-6 py-6 bg-white shadow-sm rounded-lg border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Client Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 pb-2 border-b">クライアント情報</h3>

                            <div class="space-y-4">
                                <div>
                                    <x-admin::input-field 
                                        type="text" 
                                        label="クライアント名*" 
                                        name="client_name"
                                        id="client_name" 
                                        placeholder="例:アスコン商店" 
                                        value="{{ old('client_name') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('client_name')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="text" 
                                        label="フリガナ*" 
                                        name="kana" 
                                        id="kana"
                                        placeholder="例:アスコンショウテン" 
                                        value="{{ old('kana') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('kana')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="text" 
                                        label="アカウント名*" 
                                        name="account_name"
                                        id="account_name" 
                                        placeholder="例:ascon_shouten" 
                                        value="{{ old('account_name') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    <p class="mt-1 text-sm text-gray-600">半角英小文字と数字、_のみ。URLのディレクトリ名</p>
                                    @error('account_name')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="file" 
                                        label="ロゴ*" 
                                        name="logo" 
                                        id="logo"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('logo')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="url" 
                                        label="ホームページ" 
                                        name="homepage" 
                                        id="homepage"
                                        placeholder="例:https://www.ascon.co.jp" 
                                        value="{{ old('homepage') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    <p class="mt-1 text-sm text-gray-600">フロントサイトから遷移するホームページ</p>
                                    @error('homepage')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="email" 
                                        label="お問い合わせメールアドレス" 
                                        name="support_mail"
                                        placeholder="例:support@ascon.co.jp" 
                                        value="{{ old('support_mail') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('support_mail')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 pb-2 border-b">ユーザー情報</h3>

                            <div class="space-y-4">
                                <div>
                                    <x-admin::input-field 
                                        type="text" 
                                        label="ログインID*" 
                                        name="login_id" 
                                        id="login_id"
                                        placeholder="半角英数字のみ" 
                                        value="{{ old('login_id') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('login_id')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="text" 
                                        label="ユーザー名*" 
                                        name="user_name" 
                                        id="user_name"
                                        value="{{ old('user_name') }}"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('user_name')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="password" 
                                        label="パスワード*" 
                                        name="password" 
                                        id="password"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('password')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <x-admin::input-field 
                                        type="password" 
                                        label="パスワードを再度入力*" 
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                    />
                                    @error('password_confirmation')
                                        <div class="mt-1 text-sm text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-100">
                                    <p class="text-sm text-red-600">
                                        *ログインIDとパスワードは、クライアントページにログインする際に必要となりますので、大切に保管しておいてください。
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="flex justify-end mt-6 space-x-4">
                <x-admin::button 
                    :action="[
                        'form' => 'create-tenant',
                        'label' => '追加',
                        'type' => 'submit',
                        'class' => 'px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 
                                  transition-colors duration-200 shadow-sm'
                    ]">
                </x-admin::button>
                
                <form action="{{ route('admin.tenants.index') }}" method="GET" class="inline">
                    <x-admin::button 
                        :action="[
                            'label' => 'キャンセル',
                            'type' => 'submit',
                            'class' => 'px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium 
                                      hover:bg-gray-200 transition-colors duration-200 border border-gray-200'
                        ]">
                    </x-admin::button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>