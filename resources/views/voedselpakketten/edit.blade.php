<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Voedselpakket Bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Pakket {{ $voedselpakket[0]->pakketnummer }} bewerken
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Klant: {{ $voedselpakket[0]->naam }} ({{ $voedselpakket[0]->gezins_naam }})
                        </p>
                    </div>

                    <form method="POST" action="{{ route('voedselpakketten.update', $voedselpakket[0]->id) }}" class="space-y-6">
                        @csrf
                        {{-- Note: Route uses POST, but often Laravel updates use PUT/PATCH. The route list said POST .../update --}}

                        <!-- Klant ID (Hidden, logic assumes we don't change customer for existing package to avoid stock explosion complexity on client switch) -->
                        <input type="hidden" id="klant_id" name="klant_id" value="{{ $voedselpakket[0]->klant_id }}">

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
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Wijzigingen Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script type="module">
        import { VoedselpakketManager } from '/resources/js/voedselpakket-manager.js';

        $(document).ready(function() {
            const manager = new VoedselpakketManager({
                containerId: 'producten-container',
                addBtnId: 'add-product-btn',
                klantSelectId: 'klant_id',
                submitBtnSelector: 'button[type="submit"]',
                toastContainerId: 'toast-container'
            });

            // Initial Load for Edit
            const klantId = $('#klant_id').val();
            if (klantId) {
                // Initialize Manager with Client Products
                // Using the promise we added
                manager.handleKlantChange(klantId, true).then(() => {
                    // Load Existing Products provided by Controller
                    const existingProducts = @json($producten);

                    existingProducts.forEach(prod => {
                        manager.addProductRow(null, {
                            product_id: prod.product_id,
                            aantal: prod.aantal
                        });
                    });
                });
            }
        });
    </script>
</x-app-layout>
