<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-t">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes glow-circle {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.2;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.3;
            }
        }

        .animate-glow-circle {
            animation: glow-circle 10s ease-in-out infinite;
        }
    </style>
</head>

<body class="font-sans bg-slate-900 text-white flex items-center justify-center h-screen overflow-hidden">
    <main class="relative">
        <div
            class="absolute top-[-20%] left-[-20%] w-[60vw] h-[60vw] bg-purple-600 rounded-full filter blur-3xl opacity-20 animate-glow-circle">
        </div>
        <div class="absolute bottom-[-20%] right-[-20%] w-[50vw] h-[50vw] bg-sky-500 rounded-full filter blur-3xl opacity-20 animate-glow-circle"
            style="animation-delay: 5s;"></div>

        <div class="relative z-10 text-center max-w-4xl mx-auto px-4 flex flex-col items-center">

            <h1
                class="text-7xl md:text-8xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 mb-4">
                {{ config('app.name', 'Relayn') }}
            </h1>

            <p class="max-w-3xl mx-auto text-lg md:text-xl text-slate-300 mb-10">
                Lớp vỏ thông minh kết nối người dùng và quyền truy cập đến mọi hệ thống.
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl w-full mb-12">
                @php
                    $features = [
                        ['text' => 'Phân quyền người dùng theo vai trò'],
                        ['text' => 'Kết nối đến nhiều API back-end'],
                        ['text' => 'Tùy biến theo từng tổ chức sử dụng'],
                        ['text' => 'Tách biệt giao diện với hệ thống phía sau'],
                    ];
                @endphp

                @foreach ($features as $feature)
                    <div
                        class="bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10 shadow-lg flex flex-col items-center text-center space-y-3 hover:bg-white/10 transition-colors duration-300">
                        <svg class="h-8 w-8 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.06-1.06l-3.102 3.101-1.537-1.536a.75.75 0 00-1.06 1.06l2.067 2.066a.75.75 0 001.06 0l3.633-3.633z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-slate-200 text-sm font-medium">
                            {{ $feature['text'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('filament.admin.pages.dashboard') }}"
                class="inline-block px-8 py-3 rounded-lg bg-white text-slate-900 font-bold hover:bg-slate-200 transition-transform transform hover:scale-105 shadow-2xl shadow-purple-500/20">
                Go to Dashboard
            </a>
        </div>
    </main>
</body>

</html>
