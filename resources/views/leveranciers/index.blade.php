<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Leveranciers
        </h2>
    </x-slot>

    <div class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
        <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            <!-- Titel -->
            <div class="flex justify-between items-center">
                <div class=""></div>
                <button
                    class="bg-black text-white dark:bg-gray-100 dark:text-black border mt-3 mb-3 px-4 py-2 rounded-md text-sm flex items-center gap-1">
                        Nieuwe Leverancier
                </button>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Card -->
                @forelse ($leveranciers as $leverancier) 
                    <div
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5 space-y-4">
                        <div class="flex justify-between">
                            <div class="flex gap-3">
                                <div
                                    class="w-20 h-10 bg-green-100 dark:bg-green-900/40 rounded flex items-center justify-center">
                                    🏢
                                </div>
                                <div>
                                    <h2 class="font-semibold">{{ $leverancier->bedrijfsnaam}}</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $leverancier->straat }} {{ $leverancier->huisnummer }}, {{ $leverancier->postcode }} {{ $leverancier->plaats }}
                                    </p>
                                </div>
                            </div>
                            ✏️
                        </div>

                        <div class="text-sm space-y-1 text-gray-700 dark:text-gray-300">
                            <p>👤 {{ $leverancier->contact_naam }}</p>
                            <p>📧 {{ $leverancier->email }}</p>
                            <p>📞 {{ $leverancier->telefoon }}</p>
                        </div>

                        <div
                            class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700 text-sm">
                            <span>📅 Eerstvolgende levering</span>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 dark:text-gray-400">
                                    ma 20 jan · 10:00
                                </span>
                                <span
                                    class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-xs">
                                    Gepland
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    leeg
                @endforelse
            </div>

            <!-- Leveringsoverzicht -->
            <div
                class="mt-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4">
                    <h2 class="font-semibold">Leveringsoverzicht</h2>
                    <button
                        class="bg-black text-white dark:bg-gray-100 dark:text-black border px-4 py-2 rounded-md text-sm flex items-center gap-1">
                            Nieuwe Levering
                    </button>
                </div>
                <table class="w-full text-sm">
                    <thead
                        class="bg-gray-50 dark:bg-gray-700 border-t border-b border-gray-200 dark:border-gray-600">
                        <tr class="text-left">
                            <th class="px-6 py-3">Leverancier</th>
                            <th class="px-6 py-3">Contactpersoon</th>
                            <th class="px-6 py-3">Volgende levering</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($leveringen as $levering)
                            <tr>
                                <td class="px-6 py-4">{{ $levering->bedrijfsnaam }}</td>
                                <td class="px-6 py-4">{{ $levering->contact_naam }}</td>
                                <?php 
                                $formattedDate = \Carbon\Carbon::parse($levering->eerstvolgende_levering)->format('d-m-Y H:i');
                                ?>
                                <td class="px-6 py-4">{{ $formattedDate }}</td>
                            </tr>
                        @empty
                            leeg
                        @endforelse

                    </tbody>
                </table>
            </div>

        </main>
    </div>
</x-app-layout>
