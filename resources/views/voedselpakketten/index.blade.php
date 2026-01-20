<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voedselpakketten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Voedselpakketten</h1>
                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Beheer en stel voedselpakketten samen</p>
                    </div>
                    <a href="{{ route('voedselpakketten.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nieuw pakket
                    </a>
                </div>

                <!-- Pakket Overzicht -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Pakket Overzicht
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Alle samengestelde voedselpakketten</p>
                        </div>

                        <!-- Desktop Table -->
                        <div class="hidden md:block border rounded-lg overflow-hidden dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pakket #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Klant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Familie</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($voedselpakketten as $package)
                                    {{-- {{dd($package)}} --}}

                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $package->pakketnummer }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $package->naam }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $package->gezins_naam }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $package->producten_totaal }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->datum_uitgifte ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                                    {{ $package->datum_uitgifte ? 'Uitgereikt' : 'Samengesteld' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end gap-2">
                                                    @if(!$package->datum_uitgifte)
                                                        <a href="{{ route('voedselpakketten.edit', $package->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Bewerken">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    @if(!$package->datum_uitgifte)
                                                        <form method="POST" action="{{ route('voedselpakketten.deliver', $package->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt uitreiken?');" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300" title="Markeer als uitgereikt">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('voedselpakketten.destroy', $package->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt verwijderen?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Verwijder">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                                Geen pakketten gevonden
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden space-y-4">
                            @forelse($voedselpakketten as $package)
                                <div class="border rounded-lg p-4 dark:border-gray-700 hover:shadow-md transition-shadow bg-white dark:bg-gray-800">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="font-mono font-medium text-gray-900 dark:text-white">{{ $package->pakketnummer }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $package->naam }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->datum_uitgifte ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                            {{ $package->datum_uitgifte ? 'Uitgereikt' : 'Samengesteld' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $package->gezins_naam }} • {{ $package->producten_totaal }} items</p>
                                    <div class="flex gap-2">
                                        @if(!$package->datum_uitgifte)
                                            <a href="{{ route('voedselpakketten.edit', $package->id) }}" class="flex-1 px-3 py-2 text-sm bg-blue-50 text-blue-700 rounded hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-200 text-center">Bewerken</a>
                                        @endif
                                        @if(!$package->datum_uitgifte)
                                            <form method="POST" action="{{ route('voedselpakketten.deliver', $package->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt uitreiken?');" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-2 text-sm bg-green-50 text-green-700 rounded hover:bg-green-100 dark:bg-green-900 dark:text-green-200 text-center">Afgeven</button>
                                            </form>
                                            <form method="POST" action="{{ route('voedselpakketten.destroy', $package->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt verwijderen?');" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-3 py-2 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100 dark:bg-red-900 dark:text-red-200 text-center">Verwijder</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    Geen pakketten gevonden
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
