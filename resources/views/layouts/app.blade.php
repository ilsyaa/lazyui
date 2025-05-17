<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    theme-mode="light"
    theme-accent="indigo"
    theme-aside="show"
    class="lazy"
>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script>
        window.localStorageKey = 'lazy-settings';
        window.eventKey = 'lazy-appearance-change';
    </script>
    <script src="{{ asset('assets/lazy/theme.js?v=10') }}"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="lazy-body">
    <div class="lazy-layout">
        @include('[path].layouts.sidebar')
        <main class="lazy-main">
            @include('[path].layouts.header')
            @yield('content')
        </main>
    </div>
    @livewireScripts
    @include('[path].layouts.settings')
    @stack('body')
</body>
</html>
