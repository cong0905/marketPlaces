@props(['title' => '', 'description' => '', 'image' => '', 'canonical' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' | ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>
        
        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $description ?: 'Nền tảng mua bán đồ cũ an toàn, tiện lợi. Mua bán nhanh chóng, uy tín.' }}">
        <link rel="canonical" href="{{ $canonical ?: url()->current() }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $canonical ?: url()->current() }}">
        <meta property="og:title" content="{{ $title ?: config('app.name', 'Laravel') }}">
        <meta property="og:description" content="{{ $description ?: 'Nền tảng mua bán đồ cũ an toàn, tiện lợi. Mua bán nhanh chóng, uy tín.' }}">
        @if($image)
        <meta property="og:image" content="{{ $image }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

        <style>
            .material-symbols-outlined {
                font-family: 'Material Symbols Outlined';
                font-weight: normal;
                font-style: normal;
                font-size: 24px;
                line-height: 1;
                letter-spacing: normal;
                text-transform: none;
                display: inline-block;
                white-space: nowrap;
                word-wrap: normal;
                direction: ltr;
                -webkit-font-feature-settings: 'liga';
                -webkit-font-smoothing: antialiased;
            }
        </style>

        <!-- Scripts -->
        <script>
            window.EchoConfig = {
                broadcaster: '{{ config('broadcasting.default', 'reverb') }}',
                reverbKey: '{{ config('broadcasting.connections.reverb.key') }}',
                reverbHost: '{{ config('broadcasting.connections.reverb.options.host', '127.0.0.1') }}',
                reverbPort: {{ config('broadcasting.connections.reverb.options.port', 80) }},
                reverbScheme: '{{ config('broadcasting.connections.reverb.options.scheme', 'http') }}',
                pusherKey: '{{ config('broadcasting.connections.pusher.key') }}',
                pusherCluster: '{{ config('broadcasting.connections.pusher.options.cluster', 'mt1') }}',
                pusherHost: '{{ config('broadcasting.connections.pusher.options.host', '') }}',
                pusherPort: {{ config('broadcasting.connections.pusher.options.port', 443) }},
                pusherScheme: '{{ config('broadcasting.connections.pusher.options.scheme', 'https') }}'
            };
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Reveal Animations Script -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const observerOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.15
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in-up');
                            entry.target.classList.remove('opacity-0');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
                    el.classList.add('opacity-0');
                    observer.observe(el);
                });
            });
        </script>
    </head>
    <body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-surface border-b border-outline-variant shadow-sm z-40">
                <div class="max-w-container-max mx-auto py-6 px-margin-mobile md:px-margin-desktop">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </body>
</html>
