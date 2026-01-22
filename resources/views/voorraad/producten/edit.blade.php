<x-app-layout>
    {{--
        Voorraadbeheer - Product wijzigen
        Author: Hernan Martino Molina

        This form mirrors the create-form client-side guards so editing
        can’t bypass the browser constraints.
    --}}
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product wijzigen') }}
            </h2>
            <div class="flex items-center gap-2">
                @if(auth()->user()->role === 'Directie')
                    <a href="{{ route('voorraad.categorieen.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Categorieën
                    </a>
                @endif

                <a href="{{ route('voorraad.producten.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Terug
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('voorraad.producten.update', $product->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Productnaam</label>
                            <input type="text"
                                   name="product_naam"
                                   value="{{ old('product_naam', $product->product_naam ?? '') }}"
                                   maxlength="20"
                                   autocomplete="off"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            {{-- Field-level validation error --}}
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
                                    <option value="{{ $cat->id }}" {{ (string) old('categorie_id', $product->categorie_id ?? '') === (string) $cat->id ? 'selected' : '' }}>
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
                                   value="{{ old('ean', $product->ean ?? '') }}"
                                   inputmode="numeric"
                                   autocomplete="off"
                                   minlength="13"
                                   maxlength="13"
                                   pattern="^[0-9]{13}$"
                                   title="Vul exact 13 cijfers in (alleen nummers)."
                                   {{-- Keep input digits-only and hard-cap at 13 --}}
                                   oninput="this.value=this.value.replace(/\D/g,'').slice(0,13);"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            {{-- Field-level validation error --}}
                            @error('ean')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aantal in voorraad</label>
                            <input type="number"
                                   name="aantal_voorraad"
                                   value="{{ old('aantal_voorraad', $product->aantal_voorraad ?? 0) }}"
                                   min="0"
                                   max="1000"
                                   step="1"
                                   inputmode="numeric"
                                   {{-- Clamp value to 0..1000 client-side --}}
                                   oninput="if(this.value==='') return; const n=parseInt(this.value,10); if(Number.isNaN(n)) { this.value='0'; return; } this.value=Math.max(0, Math.min(1000, n));"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            {{-- Field-level validation error --}}
                            @error('aantal_voorraad')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Allergieën</label>

                            @php
                                $currentAllergieIds = old('allergie_ids', $selectedAllergieIds ?? []);
                                $currentAllergieIds = array_map('intval', is_array($currentAllergieIds) ? $currentAllergieIds : []);
                            @endphp

                            @if(($allergenen ?? collect())->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($allergenen as $a)
                                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <input type="checkbox"
                                                   name="allergie_ids[]"
                                                   value="{{ $a->id }}"
                                                   {{ in_array((int) $a->id, $currentAllergieIds, true) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                            <span>{{ $a->naam }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Geen allergieën beschikbaar.
                                </div>
                            @endif

                            @error('allergie_ids')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                            @error('allergie_ids.*')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product kenmerken (wensen)</label>

                            @php
                                $currentWensIds = old('wens_ids', $selectedWensIds ?? []);
                                $currentWensIds = array_map('intval', is_array($currentWensIds) ? $currentWensIds : []);
                            @endphp

                            @if(($wensen ?? collect())->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($wensen as $w)
                                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <input type="checkbox"
                                                   name="wens_ids[]"
                                                   value="{{ $w->id }}"
                                                   {{ in_array((int) $w->id, $currentWensIds, true) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                            <span>{{ $w->omschrijving }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Geen kenmerken beschikbaar.
                                </div>
                            @endif

                            @error('wens_ids')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                            @error('wens_ids.*')
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

