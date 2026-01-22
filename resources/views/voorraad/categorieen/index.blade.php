<x-app-layout>
    {{--
        Voorraadbeheer - Categorieën overzicht
        Author: Hernan Martino Molina

        This screen lists active categories and provides links/actions for CRUD.
    --}}
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categorieoverzicht') }}
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('voorraad.producten.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Producten
                </a>

                <a href="{{ route('voorraad.categorieen.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Categorie aanmaken
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Naam
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acties
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($categorieen as $cat)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $cat->naam }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                {{-- Edit action --}}
                                                <a href="{{ route('voorraad.categorieen.edit', $cat->id) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Wijzigen
                                                </a>
                                                {{-- Delete action (protected by a confirm dialog) --}}
                                                <form method="POST" action="{{ route('voorraad.categorieen.destroy', $cat->id) }}"
                                                      class="inline"
                                                      onsubmit="return confirm('Weet je zeker dat je deze categorie wilt verwijderen?');">
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
                                        <td colspan="2" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Er zijn geen categorieën beschikbaar.
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

