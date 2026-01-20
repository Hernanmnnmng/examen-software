<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pakket Details') }}
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
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('voedselpakketten.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Terug naar overzicht
                </a>
            </div>

            @php
                $pakket = $voedselpakket[0];
            @endphp

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Header Section --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 dark:border-gray-700 pb-6 mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $pakket->pakketnummer }}</h1>
                            <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                {{-- Datum samenstelling was verwijderd uit SP, tonen indien beschikbaar of anders weglaten --}}
                                @if(isset($pakket->datum_samenstelling))
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Aangemaakt: {{ \Carbon\Carbon::parse($pakket->datum_samenstelling)->format('d-m-Y H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex items-center gap-3">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $pakket->datum_uitgifte ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                                <span class="w-2 h-2 rounded-full mr-2 {{ $pakket->datum_uitgifte ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                                {{ $pakket->datum_uitgifte ? 'Uitgereikt op ' . \Carbon\Carbon::parse($pakket->datum_uitgifte)->format('d-m-Y') : 'Klaar om uitgereikt te worden' }}
                            </span>
                            
                            {{-- Action buttons --}}
                            @if(!$pakket->datum_uitgifte)
                                <a href="{{ route('voedselpakketten.edit', $pakket->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Bewerken
                                </a>
                                
                                <form method="POST" action="{{ route('voedselpakketten.deliver', $pakket->id) }}" onsubmit="return confirm('Weet u zeker dat u dit pakket wilt uitreiken?');" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Afgeven
                                    </button>
                                </form>
                            @else
                                <button @click="showError('Dit pakket is al uitgereikt en kan niet meer bewerkt worden.')" class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                    Bewerken
                                </button>
                                <button @click="showError('Dit pakket is al uitgereikt.')" class="inline-flex items-center px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                    Afgeven
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Klantinformatie</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Naam</dt>
                                        <dd class="text-sm text-gray-900 dark:text-white">{{ $pakket->naam }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Familienaam</dt>
                                        <dd class="text-sm text-gray-900 dark:text-white">{{ $pakket->gezins_naam }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Samenvatting</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Totaal aantal producten</dt>
                                        <dd class="text-sm font-bold text-gray-900 dark:text-white">{{ $pakket->producten_totaal }} stuks</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- Products List --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Inhoud Pakket</h3>
                    <div class="border rounded-lg overflow-hidden dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">EAN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Categorie</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aantal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($producten as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->naam }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $product->ean }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">

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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $product->categorie }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $product->aantal }}x</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Geen producten gevonden in dit pakket.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
