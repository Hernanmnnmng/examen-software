<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Leveranciers
        </h2>
    </x-slot>

        <div class="pl-4">
            @if (session('success'))
                <div class="p-4 border border-green-600 bg-green-950/50 rounded-lg mt-1 shadow-xl mb-5" role="alert">
                    <h6 class="font-bold">{{ session('success') }}</h6>
                </div>
                <meta http-equiv="refresh" content="2;url={{ route('leveranciers.index') }}">
            @endif
        </div>

        <div class="pl-4">
            @if (session('error'))
                <div class="p-4 border border-green-600 bg-green-950/50 rounded-lg mt-1 shadow-xl mb-5" role="alert">
                    <h6 class="font-bold">{{ session('error') }}</h6>
                </div>
                <meta http-equiv="refresh" content="2;url={{ route('leveranciers.index') }}">
            @endif
        </div>

    <div class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
        <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">

            <!-- Titel -->
            <div class="flex justify-between items-center">
                <div class=""></div>
                <button
                    onclick="showNewLeverancierForm()"
                    class="bg-black text-white dark:bg-gray-100 dark:text-black border mt-3 mb-3 px-4 py-2 rounded-md text-sm flex items-center gap-1">
                        Nieuwe Leverancier
                </button>
            </div>
            <div id="NewLeverancierFormModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-lg p-6 relative transition-colors duration-200">
                    <!-- Close button -->
                    <button 
                        type="button" 
                        onclick="showNewLeverancierForm()" 
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-white">
                        ✕
                    </button>

                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Nieuwe Leverancier</h2>

                    <form method="POST" action="{{ route('leveranciers.storeLeverancier') }}" class="grid grid-cols-2 gap-4" id="newLeverancier">
                        @csrf
                        <div class="col-span-2">
                            <h6>Bedrijfs informatie</h6>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Bedrijfsnaam</label>
                            <input type="text" name="bedrijfsnaam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>

                        <div class="col-span-2">
                            <h6>Contactpersoon informatie</h6>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Contactpersoon</label>
                            <input type="text" name="contact_naam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Telefoon</label>
                            <input type="tel" name="telefoon" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>

                        <div class="col-span-2">
                            <h6>Adress gegevens</h6>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Straat</label>
                            <input type="text" name="straat" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Huisnummer</label>
                            <input type="text" name="huisnummer" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Postcode</label>
                            <input type="text" name="postcode" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Plaats</label>
                            <input type="text" name="plaats" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>


                        <div class="col-span-2 flex justify-end gap-2 mt-4">
                            <button type="submit" form="newLeverancier" class="bg-black hover:bg-black/50 text-white px-4 py-2 rounded-md text-sm font-semibold">Opslaan</button>
                            <button type="button" onclick="showNewLeverancierForm()" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold">Annuleren</button>
                        </div>
                    </form>
                </div>
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
                    </div>
                @empty
                    leeg
                @endforelse
            </div>


            <div id="NewLeveringFormModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-lg p-6 relative transition-colors duration-200">
                    <!-- Close button -->
                    <button 
                        type="button" 
                        onclick="showNewLeveringForm()" 
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-white">
                        ✕
                    </button>

                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Nieuwe Leverancier</h2>

                    <form method="POST" action="" class="grid grid-cols-2 gap-4" id="newLevering">
                        <!-- Voorbeeld inputs -->
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Bedrijfsnaam</label>
                            <input type="text" name="bedrijfsnaam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Contactpersoon</label>
                            <input type="text" name="contact_naam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Telefoon</label>
                            <input type="text" name="telefoon" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>
                        <div class="col-span-2 flex justify-end gap-2 mt-4">
                            <button type="submit" form="newLevering" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md text-sm font-semibold">Opslaan</button>
                            <button type="button" onclick="showNewLeveringForm()" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold">Annuleren</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Leveringsoverzicht -->
            <div
                class="mt-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4">
                    <h2 class="font-semibold">Leveringsoverzicht</h2>
                    <button
                        onclick="showNewLeveringForm()" 
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
                            <tr>Er zijn momenteel nog geen leveringen gemaakt.</tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="{{ asset('modal.js') }}"></script>
</x-app-layout>
