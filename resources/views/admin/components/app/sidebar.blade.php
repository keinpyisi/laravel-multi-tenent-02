<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-white p-4 transition-all duration-200 ease-in-out rounded-r-2xl shadow-sm"
        :class="{
            'translate-x-0': sidebarOpen,
            '-translate-x-64': !sidebarOpen,
            'lg:w-64': sidebarExpanded,
            'lg:w-20': !sidebarExpanded
        }"
        @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false"
    >

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-600 hover:text-gray-900" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="https://ascon.co.jp/">
                <svg id="op_ascon_logo" class="op_ascon_logo is-done" xmlns="http://www.w3.org/2000/svg" width="66"
                    height="32" viewBox="-1 0 303 99">
                    <path class="st0" fill="#E52183" stroke="#E52183" d="M117.3,27.3l-0.3,2.8h-12.3l0.5-2.8c1.3-7.7-3.1-13.5-10.2-13.8c-7.7,0-14.1,6.1-14.6,13.8c-0.5,8.2,5.6,11.8,12.3,14.3
                        c13.8,5.1,22,17.4,19,31.9c-2.6,12.5-12,24.8-29.2,23.2c-13.6-1-23.1-13.3-20.5-28.4c0-1.5,0.3-3.1,0.8-4.9h11.8
                        c-0.5,1.5-0.8,3.1-1,4.6c-1,8.2,3.1,13.3,10,14.3c7.4,0.5,14.1-4.9,15.1-12.3c1.5-8.7-6.1-12.8-13.8-16.6c-11-5.1-19-12.8-16.6-27.8
                        C69.9,11.5,81.9,0.5,96.3,0C109.8,0,119.8,12.3,117.3,27.3 M178,27.3l-1.8,10.7h-12.3l1.8-10.7c1.3-7.7-3.3-13.8-10.2-13.8
                        c-7.7,0.3-14.1,6.1-14.9,13.8l-6.7,41.1c-1.3,7.7,3.3,14.3,10.2,14.3c7.4,0,13.3-5.1,15.1-15.3l1.5-9.7h12.3l-1.8,10.7
                        c-3.6,23.8-19.2,29.6-29.5,28.4c-13.6-1.5-22.5-13.3-20-28.4l6.4-41.1C131.6,8.9,143.9,0,157.5,0C171,0,180.3,12.3,178,27.3
                         M232,68.7c-3.6,23.8-19.2,29.4-29.5,28.1c-13.6-1.5-22.5-13.3-20-28.4l6.7-41.1C192.3,8.9,204.6,0,218.2,0
                        c13.6,0,22.8,12.3,20.2,27.3L232,68.7 M216.1,13.5c-7.7,0.3-14.1,6.1-14.6,13.8l-6.7,41.1c-1.3,7.7,3.1,14.3,10.2,14.3
                        c7.4,0,13.3-5.1,15.1-15.3l6.4-40.4c1.5-5.9-2-12-8.2-13.3c0,0,0,0-0.3,0l0,0C217.7,13.5,216.9,13.5,216.1,13.5 M57.1,27.3
                        C59.4,12.3,50.1,0,36.6,0S9.9,12.3,7.4,27.3l-0.5,2.8h12.3l0.5-2.8c1-7.4,7.2-13.3,14.9-13.8c6.9,0,11.5,6.1,10.2,13.8L43,38.1H30.4
                        c-13.6,0-26.1,9.5-29.7,27.3s6.9,30.4,20.5,30.4c5.1,0,10-1.5,14.1-4.3c2.8,2.8,6.9,4.1,10.8,3.1l3.3-21.2L57.1,27.3 M38.4,68.7
                        c-1,7.4-7.2,13.3-14.9,13.8c-6.9,0-13.1-5.1-10.5-16.9c1.3-7.7,7.7-13.3,15.4-13.8h12.6L38.4,68.7z M262,27.3
                        c1.5-9.2,7.7-13.8,14.9-13.8c7.2,0,11.5,6.1,10.2,13.8l-10.5,66.9h12.6l10-64.4c4.1-20.4-6.7-29.9-20-29.9c-5.1,0-10,1.5-14.1,4.3
                        c-2.8-2.8-6.9-4.1-10.8-3.1l-14.9,93h12.3L262,27.3z"></path>
                </svg>
            </a>
            @if (App::environment('local') || App::environment('staging'))
                <div
                    class="inline-block border-2 text-sm px-2 py-1 font-medium bg-white shadow-[0_0_0_2px_rgba(0,0,0,0.2)]
                @if (App::environment('local')) border-blue-500 text-blue-700
                @elseif(App::environment('staging')) border-yellow-500 text-yellow-700
                @elseif(App::environment('production')) border-green-500 text-green-700 @endif">
                    @if (App::environment('local'))
                        検証環境 (Local)
                    @elseif(App::environment('staging'))
                        検証環境 (Staging)
                    @endif
                </div>
            @endif
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <div>
                <h3 class="text-xs uppercase text-gray-600 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        :class="{'hidden': sidebarExpanded}" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block"
                        :class="{'!block': sidebarExpanded}">Pages</span>
                </h3>
                <ul class="mt-3">
                    <!-- Menu items with x-data for expansion -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))]"
                        :class="{'from-violet-500/[0.12] to-violet-500/[0.04]': isActive}"
                        x-data="{ isActive: {{ in_array(Request::segment(3), ['tenants']) ? 'true' : 'false' }}, open: {{ in_array(Request::segment(3), ['tenants']) ? 'true' : 'false' }} }">
                        <a class="block text-gray-700 truncate transition @if (!in_array(Request::segment(3), ['tenants'])) {{ 'hover:text-gray-900' }} @endif"
                            href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current @if (in_array(Request::segment(3), ['tenants'])) {{ 'text-violet-600' }}@else{{ 'text-gray-600' }} @endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                        <path
                                            d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                        :class="{ 'lg:opacity-100': sidebarExpanded }">クライアント</span>
                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-600 @if (in_array(Request::segment(3), ['tenants'])) {{ 'rotate-180' }} @endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if (!in_array(Request::segment(3), ['tenants'])) {{ 'hidden' }} @endif"
                                :class="open ? '!block' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-600 hover:text-gray-900 transition truncate @if (Route::is('admin.tenants.index')) {{ 'text-violet-600' }} @endif"
                                        href="{{ route('admin.tenants.index') }}">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                            :class="{ 'lg:opacity-100': sidebarExpanded }">クライアント一覧</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-600 hover:text-gray-900 transition truncate @if (Route::is('admin.tenants.create')) {{ 'text-violet-600' }} @endif"
                                        href="{{ route('admin.tenants.create') }}">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                            :class="{ 'lg:opacity-100': sidebarExpanded }">クライアント作成</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Settings -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-[linear-gradient(135deg,var(--tw-gradient-stops))]"
                        :class="{'from-violet-500/[0.12] to-violet-500/[0.04]': isActive}"
                        x-data="{ 
                            isActive: {{ in_array(Request::segment(3), ['users', 'maitenance']) ? 'true' : 'false' }}, 
                            open: {{ in_array(Request::segment(3), ['users', 'maitenance']) ? 'true' : 'false' }} 
                        }">
                        <a class="block text-gray-700 truncate transition hover:text-gray-900"
                            href="#0" 
                            @click.prevent="open = !open; $parent.sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current" 
                                        :class="isActive ? 'text-violet-600' : 'text-gray-600'"
                                        xmlns="http://www.w3.org/2000/svg" 
                                        width="16" 
                                        height="16"
                                        viewBox="0 0 16 16">
                                        <path d="M10.5 1a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2h-1.145a3.502 3.502 0 0 1-6.71 0H1a1 1 0 0 1 0-2h6.145A3.502 3.502 0 0 1 10.5 1ZM9 4.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM5.5 9a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2H8.855a3.502 3.502 0 0 1-6.71 0H1a1 1 0 1 1 0-2h1.145A3.502 3.502 0 0 1 5.5 9ZM4 12.5a1.5 1.5 0 1 0 3 0 1.5 1.5 0 0 0-3 0Z"
                                            fill-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                        :class="{ 'lg:opacity-100': $parent.sidebarExpanded }">
                                        設定
                                    </span>
                                </div>
                                <!-- Icon -->
                                <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                    :class="{ 'lg:opacity-100': $parent.sidebarExpanded }">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-600 transition-transform duration-200"
                                        :class="{ 'rotate-180': open }"
                                        viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1"
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-600 hover:text-gray-900 transition truncate {{ Route::is('admin.users.index') ? 'text-violet-600' : '' }}"
                                        href="{{ route('admin.users.index') }}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                            :class="{ 'lg:opacity-100': $parent.sidebarExpanded }">
                                            アカウント設定
                                        </span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-600 hover:text-gray-900 transition truncate {{ Route::is('admin.maitenance.index') ? 'text-violet-600' : '' }}"
                                        href="{{ route('admin.maitenance.index') }}">
                                        <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                                            :class="{ 'lg:opacity-100': $parent.sidebarExpanded }">
                                            メインテナンス設定
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                </ul>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button class="text-gray-600 hover:text-gray-900 transition-colors"
                    @click.prevent="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current transform transition-transform duration-200"
                        :class="{ 'rotate-180': sidebarExpanded }"
                        viewBox="0 0 24 24">
                        <path d="M13.293 6.293L7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>