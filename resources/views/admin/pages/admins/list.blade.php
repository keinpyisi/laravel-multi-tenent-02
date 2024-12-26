<x-app-layout>
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                クライアント
                <form action="{{ route('admin.tenants.create') }}" method="GET" class="inline">
                    <x-admin::button :action="[
                        'label' => '新規',
                        'type' => 'button',
                        'class' =>
                            'transition duration-150 ease-in-out px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
                    ]">
                    </x-admin::button>
                </form>
            </h2>
        </div>
        <!-- Breadcrumb End -->

        <!-- ====== Table Section Start -->
        <div class="flex flex-col gap-10">
            @php
                $headers = ['ID', 'クライアントの名前', 'ロゴ', '備考', '操作'];
                $rows[] = [];
                foreach ($tenents as $tenent) {
                    // Extracting domain and filename from the tenant logo path
                    $logoPath = $tenent->logo; // e.g., 'tenants/ecos/logo/logo-dark.png'
                    $domain = explode('/', $logoPath)[0]; // ecos
                    $file = basename($logoPath); // logo-dark.png
                    $rows[] = [
                        $tenent->id,
                        $tenent->client_name,
                        "<img src='" .
                        route('tenant.logo', ['domain' => $domain, 'file' => $file]) .
                        "' alt='Logo' class='h-25 w-35 object-cover'>",
                        $tenent->note,
                        [
                            [
                                'label' => 'リンク',
                                'class' =>
                                    'link_btn transition duration-150 ease-in-out px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500',
                                'type' => 'button',
                                'id' => 'link_btn',
                                'url' => route('tenant.users.login', ['tenant' => $tenent->domain]), // Dynamically generating the route
                                'method' => 'GET',
                                'which_type' => 'submit',
                                'target' => 'new', // This will open in a new window/tab
                            ],
                            [
                                'label' => '詳細',
                                'class' =>
                                    'detail_btn transition duration-150 ease-in-out px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500',
                                'id' => 'detail_btn',
                                'url' => route('admin.tenants.show', [$tenent]), // Dynamically setting the detail URL
                                'type' => 'submit',
                                'which_type' => 'submit',
                                'data-id' => $tenent->id,
                            ],
                            [
                                'label' => '削除',
                                'class' =>
                                    'delete_btn transition duration-150 ease-in-out px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500',
                                'id' => 'delete_btn',
                                'url' => route('admin.tenants.destroy', [$tenent->id]),
                                'method' => 'DELETE',
                                'type' => 'button',
                                'which_type' => 'button',
                                'data-id' => $tenent->id,
                            ],
                        ],
                    ];
                }

            @endphp
            <x-admin::tables :headers="$headers" :rows="$rows" />
            <!-- Pagination Section -->
            <div class="mt-6">
                <x-admin::paginations :paginator="$tenents" /> <!-- Pagination links -->
            </div>
        </div>
        <!-- ====== Table Section End -->
    </div>
</x-app-layout>
