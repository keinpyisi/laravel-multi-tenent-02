<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script>
            window.Laravel = {
                success: @json(session('success')),
                error: @json(session('error'))
            };
            window.Lang = @json(__('lang')); // Assuming 'lang' is your language file
        </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Include additional JS from child view if provided -->
        @if (isset($header_js_defines) && !empty($header_js_defines))
            @vite($header_js_defines)
        @endif
        @if (isset($header_css_defines) && !empty($header_css_defines))
            @vite($header_css_defines)
        @endif
        @if (isset($header_js_variables) && !empty($header_js_variables))
            <script>
                (function() {
                    var jsVars = @json($header_js_variables);
                    for (var key in jsVars) {
                        if (jsVars.hasOwnProperty(key)) {
                            window[key] = jsVars[key];
                        }
                    }
                })();
            </script>
        @endif
        <style>
            @media (min-width: 1024px) {
                .sidebar-expanded .lg\:sidebar-expanded\:block {
                    display: block;
                }
                .sidebar-expanded .lg\:sidebar-expanded\:!w-64 {
                    width: 16rem !important;
                }
                .sidebar-expanded .lg\:sidebar-expanded\:opacity-100 {
                    opacity: 1;
                }
            }
        </style>
    </head>
    <body class="font-inter antialiased bg-white text-gray-800"
        x-data="{ 
            sidebarOpen: false, 
            sidebarExpanded: localStorage.getItem('sidebar-expanded') === 'true' 
        }" 
        :class="{ 'sidebar-expanded': sidebarExpanded }"
        x-init="
            $watch('sidebarExpanded', value => {
                localStorage.setItem('sidebar-expanded', value);
                if (value) {
                    document.querySelector('body').classList.add('sidebar-expanded');
                } else {
                    document.querySelector('body').classList.remove('sidebar-expanded');
                }
            });
        "
    >
    <script>
         // Initialize sidebar state
        if (localStorage.getItem('sidebar-expanded') === null) {
            localStorage.setItem('sidebar-expanded', true);
        }
    </script>

    <!-- Page wrapper -->
    <div class="flex min-h-screen overflow-hidden">

        <x-admin::app.sidebar :variant="$attributes['sidebarVariant']" />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden @if ($attributes['background']) {{ $attributes['background'] }} @endif"
            x-ref="contentarea">
            <x-admin::app.header :variant="$attributes['headerVariant']" />

            <main class="grow">
                {{ $slot }}
            </main>

        </div>

    </div>

    </body>

    <footer class="bg-gray-200 text-gray-700 text-center py-4 lg:text-left">
        <!--Copyright section-->
        <div class="bg-gray-300 p-6 text-center">
            <span>Copyright © 2023</span>
            <a class="font-semibold text-blue-600 hover:text-blue-800" href="https://ascon.co.jp/">ASCON Co.,Ltd. All Rights Reserved.</a>
        </div>
    </footer>
</html>
