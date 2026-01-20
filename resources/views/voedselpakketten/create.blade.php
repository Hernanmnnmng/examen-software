<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuw Voedselpakket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nieuw Pakket Samenstellen
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Stel een nieuw voedselpakket samen</p>
                    </div>

                    <form method="POST" action="{{ route('voedselpakketten.store') }}" class="space-y-6">
                        @csrf
                        <!-- Klant Selectie -->
                        <div>
                            <label for="klant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Klant</label>
                            <select id="klant_id" name="klant_id" required class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Selecteer een klant --</option>
                                @foreach($klanten as $klant)
                                    <option value="{{ $klant->id }}">
                                        {{ $klant->naam }} ({{ $klant->gezins_naam }})
                                    </option>
                                @endforeach
                            </select>
                            @error('klant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Producten Toevoegen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Producten</label>
                            <div id="producten-container" class="space-y-3">
                                {{-- Rows will be added dynamically --}}
                            </div>
                            <button type="button" id="add-product-btn" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">+ Nog een product</button>
                            @error('producten')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="flex gap-3">
                            <a href="{{ route('voedselpakketten.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Annuleren</a>
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Pakket Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script type="module">
        import { VoedselpakketManager } from '/js/voedselpakket-manager.js';

        $(document).ready(function() {
            const manager = new VoedselpakketManager({
                containerId: 'producten-container',
                addBtnId: 'add-product-btn',
                klantSelectId: 'klant_id',
                submitBtnSelector: 'button[type="submit"]',
                toastContainerId: 'toast-container'
            });
        });
    </script>
</x-app-layout>
