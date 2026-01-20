<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bewerk Leverancier
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10">
        @if(session('error'))
            <div class="p-4 mb-5 border border-red-600 bg-red-950/50 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('leveranciers.updateleverancier', $leverancier->id) }}" class="grid grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            <div class="col-span-2">
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Bedrijfsnaam</label>
                <input type="text" name="bedrijfsnaam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>

            <div class="col-span-2 flex justify-end gap-2 mt-4">
                <button type="submit" class="bg-black hover:bg-black/50 text-white px-4 py-2 rounded-md text-sm font-semibold">Opslaan</button>
                <a href="{{ route('leveranciers.index') }}" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold">Annuleren</a>
            </div>
        </form>
    </div>
</x-app-layout>
