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
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform scale-90"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         x-init="setTimeout(() => show = false, 5000)"
                         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 rounded-xl shadow-2xl overflow-hidden max-w-2xl w-11/12 md:w-full bg-white"
                         style="display: none;" x-show.important="show">
                        <div class="bg-green-500 text-white px-8 py-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-green-400 p-3 rounded-lg">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></path></svg>
                                </div>
                                <h3 class="text-xl font-bold">Succes!</h3>
                            </div>
                        </div>
                        <div class="px-8 py-6">
                            <p class="text-gray-600 mb-6">{{ session('success') }}</p>
                            <button @click="show = false" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-8 py-3 rounded-lg transition duration-200 w-full">Begrepen</button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform scale-90"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         x-init="setTimeout(() => show = false, 5000)"
                         class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 rounded-xl shadow-2xl overflow-hidden max-w-2xl w-11/12 md:w-full bg-white"
                         style="display: none;" x-show.important="show">
                        <div class="bg-red-500 text-white px-8 py-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-red-400 p-3 rounded-lg">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></path></svg>
                                </div>
                                <h3 class="text-xl font-bold">Fout!</h3>
                            </div>
                        </div>
                        <div class="px-8 py-6">
                            <p class="text-gray-600 mb-6">{{ session('error') }}</p>
                            <button @click="show = false" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-8 py-3 rounded-lg transition duration-200 w-full">Begrepen</button>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
