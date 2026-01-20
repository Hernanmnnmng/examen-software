<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product toevoegen') }}
            </h2>
            <a href="{{ route('voorraad.producten.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Terug
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('voorraad.producten.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Productnaam</label>
                            <input type="text"
                                   name="product_naam"
                                   value="{{ old('product_naam') }}"
                                   maxlength="20"
                                   autocomplete="off"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @error('product_naam')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categorie</label>
                            <select name="categorie_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="">Selecteer categorie</option>
                                @foreach($categorieen as $cat)
                                    <option value="{{ $cat->id }}" {{ (string) old('categorie_id') === (string) $cat->id ? 'selected' : '' }}>
                                        {{ $cat->naam }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie_id')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">EAN (13 cijfers)</label>
                            <input type="text"
                                   name="ean"
                                   value="{{ old('ean') }}"
                                   inputmode="numeric"
                                   autocomplete="off"
                                   minlength="13"
                                   maxlength="13"
                                   pattern="^[0-9]{13}$"
                                   title="Vul exact 13 cijfers in (alleen nummers)."
                                   oninput="this.value=this.value.replace(/\D/g,'').slice(0,13);"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @error('ean')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aantal in voorraad</label>
                            <input type="number"
                                   name="aantal_voorraad"
                                   value="{{ old('aantal_voorraad', 0) }}"
                                   min="0"
                                   max="1000"
                                   step="1"
                                   inputmode="numeric"
                                   oninput="if(this.value==='') return; const n=parseInt(this.value,10); if(Number.isNaN(n)) { this.value='0'; return; } this.value=Math.max(0, Math.min(1000, n));"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @error('aantal_voorraad')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

