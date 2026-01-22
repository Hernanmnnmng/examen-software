<x-app-layout>
    {{--
        Voorraadbeheer - Categorie aanmaken
        Author: Hernan Martino Molina

        Form posts to voorraad.categorieen.store.
    --}}
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4 flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categorie aanmaken') }}
            </h2>
            <a href="{{ route('voorraad.categorieen.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Terug
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('voorraad.categorieen.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categorienaam</label>
                            <input type="text" name="naam" value="{{ old('naam') }}"
                                   maxlength="20"
                                   pattern="^[^0-9]{1,20}$"
                                   title="Alleen tekst (geen cijfers), maximaal 20 tekens"
                                   oninput="this.value=this.value.replace(/[0-9]/g,'').slice(0,20)"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            {{-- Field-level validation error --}}
                            @error('naam')
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

