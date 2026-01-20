<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nieuw Voedselpakket') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data='{
        showErrorModal: {{ $errors->has('producten') ? 'true' : 'false' }},
        errorMessage: @json($errors->first('producten'))
    }'>
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

                        <!-- Klant Selectie wijzen-->
                        <div>
                            <label for="klant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Klant</label>
                            <select id="klant_id" name="klant_id" required class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Selecteer een klant --</option>
                                @foreach($klanten as $klant)
                                    <option value="{{ $klant->id }}" {{ old('klant_id') == $klant->id ? 'selected' : '' }}>
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

        <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

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
                                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Fout bij opslaan</h3>
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

    @php
        $errorIndices = collect($errors->keys())
            ->filter(fn($key) => str_starts_with($key, 'producten.') && is_numeric(explode('.', $key)[1] ?? null))
            ->map(fn($key) => (int)explode('.', $key)[1])
            ->unique()
            ->values()
            ->all();
    @endphp

    <script type="module">
        import { VoedselpakketManager } from '/js/voedselpakket-manager.js';

        document.addEventListener('DOMContentLoaded', () => {
            const manager = new VoedselpakketManager({
                containerId: 'producten-container',
                addBtnId: 'add-product-btn',
                klantSelectId: 'klant_id',
                submitBtnSelector: 'button[type="submit"]',
                toastContainerId: 'toast-container',
                errorIndices: @json($errorIndices)
            });

            // HERSTEL OUDE DATA (bij validatie fouten)
            const oldKlantId = document.getElementById('klant_id').value;
            const oldProducts = @json(old('producten', []));

            if (oldKlantId && oldProducts && (Array.isArray(oldProducts) ? oldProducts.length > 0 : Object.keys(oldProducts).length > 0)) {
                manager.fetchProductsForClient(oldKlantId, true).then(() => {
                    Object.keys(oldProducts).forEach(key => {
                        const prod = oldProducts[key];
                        manager.addProductRow({
                            product_id: prod.product_id,
                            aantal: prod.aantal
                        }, key);
                    });
                });
            }
        });
    </script>
</x-app-layout>
