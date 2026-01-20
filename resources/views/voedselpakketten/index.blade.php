<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voedselpakketten') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        showErrorModal: false,
        errorMessage: '',
        showError(msg) {
            this.errorMessage = msg;
            this.showErrorModal = true;
        }
    }">
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
                                Pakket Overzicht
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Alle samengestelde voedselpakketten</p>
                        </div>

                        <!-- Desktop Table voor grote schermen -->
                        <div class="hidden md:block border rounded-lg overflow-hidden dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <!-- Tabel Headers -->
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pakket #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Klant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Familie</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Loop door alle pakketten -->
                                    @forelse($voedselpakketten as $package)
                                    {{-- {{dd($package)}} --}}

                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('voedselpakketten.show', $package->id) }}" class="font-mono font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                    {{ $package->pakketnummer }}
                                                </a>
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
                                                    {{-- Actie knoppen --}}
                                                    @if(!$package->datum_uitgifte)
                                                        {{-- Normale acties voor actieve pakketten --}}
                                                        <a href="{{ route('voedselpakketten.edit', $package->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Bewerken">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>

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
                                                    @else
                                                        {{-- Uitgeschakelde knoppen met melding bij klik --}}
                                                        <button @click="showError('Dit pakket is al uitgereikt en kan niet meer bewerkt worden.')" class="text-gray-400 cursor-not-allowed" title="Reeds uitgereikt">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </button>

                                                        <button @click="showError('Dit pakket is al uitgereikt.')" class="text-gray-400 cursor-not-allowed" title="Reeds uitgereikt">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </button>

                                                        <button @click="showError('Dit pakket is al uitgereikt en kan niet verwijderd worden.')" class="text-gray-400 cursor-not-allowed" title="Reeds uitgereikt">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
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
                                            <a href="{{ route('voedselpakketten.show', $package->id) }}" class="font-mono font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                {{ $package->pakketnummer }}
                                            </a>
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

                                            <form method="POST" action="{{ route('voedselpakketten.deliver', $package->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt uitreiken?');" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-2 text-sm bg-green-50 text-green-700 rounded hover:bg-green-100 dark:bg-green-900 dark:text-green-200 text-center">Afgeven</button>
                                            </form>

                                            <form method="POST" action="{{ route('voedselpakketten.destroy', $package->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt verwijderen?');" class="flex-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-3 py-2 text-sm bg-red-50 text-red-700 rounded hover:bg-red-100 dark:bg-red-900 dark:text-red-200 text-center">Verwijder</button>
                                            </form>
                                        @else
                                            <button @click="showError('Dit pakket is al uitgereikt en kan niet meer bewerkt worden.')" class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded cursor-not-allowed dark:bg-gray-700 text-center">Bewerken</button>
                                            <button @click="showError('Dit pakket is al uitgereikt.')" class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded cursor-not-allowed dark:bg-gray-700 text-center">Afgeven</button>
                                            <button @click="showError('Dit pakket is al uitgereikt en kan niet verwijderd worden.')" class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-400 rounded cursor-not-allowed dark:bg-gray-700 text-center">Verwijderen</button>
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

        <!-- Error Modal using Alpine.js -->
        <div x-show="showErrorModal"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showErrorModal = false"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Actie niet toegestaan</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-300" x-text="errorMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" @click="showErrorModal = false">Begrepen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
