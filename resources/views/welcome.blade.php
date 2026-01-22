<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Voedselbank Maaskantje</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Voedselbank Maaskantje</h1>
                        </div>
                        @if (Route::has('login'))
                            <div class="flex items-center gap-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                        Inloggen
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            Registreren
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="bg-green-600 dark:bg-green-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
                    <div class="text-center">
                        <h2 class="text-4xl font-extrabold text-white sm:text-5xl sm:tracking-tight lg:text-6xl">
                            Welkom bij Voedselbank Maaskantje
                        </h2>
                        <p class="mt-6 max-w-2xl mx-auto text-xl text-green-100">
                            Samen zorgen we voor een betere toekomst. Wij helpen mensen in nood door het verstrekken van voedselpakketten en ondersteuning.
                        </p>
                        @guest
                            <div class="mt-10 flex justify-center gap-4">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Inloggen
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                                        Account aanmaken
                                    </a>
                                @endif
                            </div>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-16 bg-white dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white">Onze Missie</h3>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                            Niemand hoeft honger te lijden
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1 -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Voedselpakketten</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Wekelijks verstrekken we voedselpakketten aan gezinnen die onze hulp nodig hebben.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Vrijwilligers</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Ons team van vrijwilligers zet zich dagelijks in voor mensen in nood.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm">
                            <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-600 text-white mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Samen Sterk</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Samen met partners en donateurs maken we het verschil in onze gemeenschap.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <p class="text-center text-gray-500 dark:text-gray-400 text-sm">
                        &copy; {{ date('Y') }} Voedselbank Maaskantje. Samen zorgen we voor een betere toekomst.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
