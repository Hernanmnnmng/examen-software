<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Voorraadbeheer') }}
            </h2>

            <div class="flex items-center gap-2">
                @if(auth()->user()->role === 'Directie')
                    <a href="{{ route('voorraad.categorieen.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Categorieën
                    </a>
                @endif

                <a href="{{ route('voorraad.producten.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Product toevoegen
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('voorraad.producten.index') }}" class="flex gap-4 flex-wrap items-end">
                    <div class="flex-1 min-w-[240px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zoek op EAN</label>
                        <input type="text" name="ean" value="{{ $ean }}"
                               placeholder="13-cijferige EAN..."
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Zoeken
                    </button>
                    <a href="{{ route('voorraad.producten.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    @php
                        $rawError = session('error');
                        $errorMessage = $rawError;

                        if (is_string($rawError) && str_contains($rawError, 'SQLSTATE[')) {
                            // Extract the MySQL SIGNAL message (e.g. "Productnaam ... bestaat")
                            // from the technical SQLSTATE blob.
                            if (preg_match('/SQLSTATE\[[^\]]+\].*?:\s*\d+\s*(.*?)\s*\(Connection:/', $rawError, $m)) {
                                $errorMessage = $m[1];
                            } elseif (preg_match('/SQLSTATE\[[^\]]+\].*?:\s*\d+\s*(.*?)\s*\(SQL:/', $rawError, $m)) {
                                $errorMessage = $m[1];
                            } else {
                                $beforeDetails = preg_split('/\s*\((Connection|SQL):/i', $rawError)[0] ?? $rawError;
                                $errorMessage = preg_replace('/^SQLSTATE\[[^\]]+\]:\s*.*?:\s*\d+\s*/', '', $beforeDetails);
                            }

                            $errorMessage = trim((string) $errorMessage);
                        }
                    @endphp
                    <span class="block sm:inline">{{ $errorMessage }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        @php
                            $makeSortUrl = function (string $column) use ($sort, $dir, $ean) {
                                $nextDir = ($sort === $column && $dir === 'asc') ? 'desc' : 'asc';
                                return route('voorraad.producten.index', array_filter([
                                    'ean' => $ean !== '' ? $ean : null,
                                    'sort' => $column,
                                    'dir' => $nextDir,
                                ]));
                            };

                            $sortIcon = function (string $column) use ($sort, $dir) {
                                if ($sort !== $column) return '';
                                return $dir === 'asc' ? '↑' : '↓';
                            };
                        @endphp

                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ $makeSortUrl('product_naam') }}" class="hover:underline">
                                            Productnaam {{ $sortIcon('product_naam') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ $makeSortUrl('categorie') }}" class="hover:underline">
                                            Categorie {{ $sortIcon('categorie') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ $makeSortUrl('ean') }}" class="hover:underline">
                                            EAN {{ $sortIcon('ean') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ $makeSortUrl('aantal_voorraad') }}" class="hover:underline">
                                            Aantal {{ $sortIcon('aantal_voorraad') }}
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acties
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($producten as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $product->product_naam }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product->categorie_naam ?? '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $product->ean }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                                {{ $product->aantal_voorraad }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('voorraad.producten.edit', $product->id) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Wijzigen
                                                </a>
                                                <form method="POST" action="{{ route('voorraad.producten.destroy', $product->id) }}"
                                                      class="inline"
                                                      onsubmit="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        Verwijderen
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Er zijn geen producten beschikbaar.
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

