<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Welkom {{ Auth::user()->name }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('voedselpakketten.index') }}" class="block p-6 bg-blue-50 dark:bg-blue-900 border border-blue-100 dark:border-blue-700 rounded-lg hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-blue-800 dark:text-blue-100 mb-2">Voedselpakketten Beheer</h4>
                            <p class="text-sm text-blue-600 dark:text-blue-200">
                                Beheer voedselpakketten, maak nieuwe aan of geef pakketten uit.
                            </p>
                        </a>

                        <!-- Ruimte voor toekomstige modules -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
