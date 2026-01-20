<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bewerk Levering
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10">
        @if(session('error'))
            <div class="p-4 mb-5 border border-red-600 bg-red-950/50 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('leveranciers.updatelevering', $levering->id) }}" class="grid grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            <div class="col-span-2">
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Bedrijf</label>
                <select name="leverancier_id" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @forelse ($leveranciers as $leverancier)
                        <option value="{{ $leverancier->id }}" @if($leverancier->id == $levering->leverancier_id) selected @endif>
                            {{ $leverancier->bedrijfsnaam }}
                        </option>
                    @empty
                        <option value="0">Geen bedrijven geregistreerd</option>
                    @endforelse
                </select>
            </div>

            <div class="col-span-2">
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Leverdatum & tijd</label>
                <input type="datetime-local" name="leverdatum_tijd" 
                    value="{{ \Carbon\Carbon::parse($levering->leverdatum_tijd)->format('Y-m-d\TH:i') }}"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div class="col-span-2">
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Eerstvolgende levering</label>
                <input type="datetime-local" name="eerstvolgende_levering" 
                    value="{{ \Carbon\Carbon::parse($levering->eerstvolgende_levering)->format('Y-m-d\TH:i') }}"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div class="col-span-2 flex justify-end gap-2 mt-4">
                <button type="submit" class="bg-black hover:bg-black/50 text-white px-4 py-2 rounded-md text-sm font-semibold">Opslaan</button>
                <a href="{{ route('leveranciers.index') }}" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold">Annuleren</a>
            </div>
        </form>
    </div>
</x-app-layout>
