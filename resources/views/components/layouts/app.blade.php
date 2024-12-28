<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @wireUiScripts
    @livewireScripts
    {{-- @livewireStyles --}}

</head>

<body class="font-sans antialiased">
    <x-wui-dialog />
    <x-wui-notifications z-index="z-[60]" position="top-right" />
    <div class="min-h-screen text-sm bg-gray-100 dark:bg-gray-900">
        <livewire:layout.mini-navigation />

        {{ $slot }}
    </div>

    @yield('script')
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}

    {{-- @livewireScriptsConfig --}}
</body>

</html>
