<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bewerk Levering
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        @if(session('error'))
            <div class="p-4 mb-6 border border-red-600 bg-red-950/50 rounded-lg">
                <p class="text-red-100">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <form method="POST" action="{{ route('leveranciers.updatelevering', $levering->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bedrijf</label>
                    <select required name="leverancier_id" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @forelse ($leveranciers as $leverancier)
                            <option value="{{ $leverancier->id }}" @if($leverancier->id == $levering->leverancier_id) selected @endif>
                                {{ $leverancier->bedrijfsnaam }}
                            </option>
                        @empty
                            <option value="0">Geen bedrijven geregistreerd</option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leverdatum & tijd</label>
                    <input type="datetime-local" name="leverdatum_tijd" 
                        value="{{ \Carbon\Carbon::parse($levering->leverdatum_tijd)->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required/>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Eerstvolgende levering</label>
                    <input type="datetime-local" name="eerstvolgende_levering" 
                        value="{{ \Carbon\Carbon::parse($levering->eerstvolgende_levering)->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required/>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('leveranciers.index') }}" class="w-full sm:w-auto text-center bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">
                        Annuleren
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-black hover:bg-black/80 dark:bg-gray-100 dark:text-black dark:hover:bg-gray-200 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">
                        Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
