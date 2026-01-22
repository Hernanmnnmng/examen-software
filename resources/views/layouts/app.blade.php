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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{-- Flash Messages --}}

                @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)">
                        <!-- Backdrop -->
                        <div x-show="show" @click="show = false"
                             class="fixed inset-0 bg-black/50 z-40"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"></div>

                        <!-- Modal -->
                        <div x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-md w-full p-8">
                                <div class="flex gap-4 mb-6">
                                    <svg class="w-8 h-8 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Succes</h3>
                                        <p class="text-gray-700 dark:text-gray-300">{{ session('success') }}</p>
                                    </div>
                                </div>
                                <button @click="show = false" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Begrepen
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)">
                        <!-- Backdrop -->
                        <div x-show="show" @click="show = false"
                             class="fixed inset-0 bg-black/50 z-40"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"></div>

                        <!-- Modal -->
                        <div x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-md w-full p-8">
                                <div class="flex gap-4 mb-6">
                                    <svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Fout</h3>
                                        <p class="text-gray-700 dark:text-gray-300">{{ session('error') }}</p>
                                    </div>
                                </div>
                                <button @click="show = false" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                    Begrepen
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
