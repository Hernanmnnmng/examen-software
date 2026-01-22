<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Leveranciers
        </h2>
    </x-slot>

    {{-- <div class="pl-2 sm:pl-4">
        @if (session('success'))
            <div class="p-4 border border-green-600 bg-green-950/50 rounded-lg mt-1 shadow-xl mb-5" role="alert">
                <h6 class="font-bold">{{ session('success') }}</h6>
            </div>
            <meta http-equiv="refresh" content="2;url={{ route('leveranciers.index') }}">
        @endif
    </div>

    <div class="pl-2 sm:pl-4">
        @if (session('error'))
            <div class="p-4 border border-red-600 bg-red-950/50 rounded-lg mt-1 shadow-xl mb-5" role="alert">
                <h6 class="font-bold">{{ session('error') }}</h6>
            </div>
            <meta http-equiv="refresh" content="2;url={{ route('leveranciers.index') }}">
        @endif
    </div> --}}

    <div class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">

            <!-- Header with Button -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold">Leveranciers</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Beheer leveranciers en leveringsschema's</p>
                </div>
                <button
                    onclick="showNewLeverancierForm()"
                    class="w-full sm:w-auto bg-blue-600 text-white dark:bg-blue-700 dark:text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                    + Nieuwe Leverancier
                </button>
            </div>

            <!-- New Leverancier Modal -->
            <div id="NewLeverancierFormModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 p-4">
                <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-2xl p-6 relative my-8">
                    <button
                        type="button"
                        onclick="showNewLeverancierForm()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-white text-2xl z-10">
                        ✕
                    </button>

                    <h2 class="text-xl sm:text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100 pr-8">Nieuwe Leverancier</h2>

                    <form method="POST" action="{{ route('leveranciers.storeLeverancier') }}" class="space-y-6" id="newLeverancier">
                        @csrf

                        <div>
                            <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-gray-100">Bedrijfs informatie</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Bedrijfsnaam</label>
                                    <input type="text" name="bedrijfsnaam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-gray-100">Contactpersoon informatie</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Contactpersoon</label>
                                    <input type="text" name="contact_naam" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" name="email" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Telefoon</label>
                                <input type="tel" pattern="^(\+31\s?0?6|0?6)\s?\d{8}$" name="telefoon" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold mb-4 text-gray-900 dark:text-gray-100">Adres gegevens</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Straat</label>
                                    <input type="text" name="straat" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Huisnummer</label>
                                    <input type="text" name="huisnummer" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Postcode</label>
                                    <input type="text" pattern="^[1-9][0-9]{3}\s?[A-Z]{2}$" name="postcode" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                    @error('postcode')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Plaats</label>
                                    <input type="text" name="plaats" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" onclick="showNewLeverancierForm()" class="w-full sm:w-auto bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">Annuleren</button>
                            <button type="submit" form="newLeverancier" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">Opslaan</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>

            <!-- Leveranciers Cards Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @forelse ($leveranciers as $leverancier)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 sm:p-5 space-y-4 hover:shadow-lg transition-shadow">
                                    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
                                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6 text-green-800 dark:text-green-200" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4 20V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M8 8h4M8 11h4M8 14h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M18 20v-9a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M10 20v-3h2v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h2 class="font-semibold text-base sm:text-lg truncate">{{ $leverancier->bedrijfsnaam }}</h2>
                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                                {{ $leverancier->straat }} {{ $leverancier->huisnummer }}, {{ $leverancier->postcode }} {{ $leverancier->plaats }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-xs sm:text-sm space-y-2 text-gray-700 dark:text-gray-300">
                                        <p class="flex items-center gap-2 min-w-0">
                                            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M20 21a8 8 0 0 0-16 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span class="truncate">{{ $leverancier->contact_naam }}</span>
                                        </p>
                                        <p class="flex items-center gap-2 min-w-0">
                                            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5v-9Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                                <path d="m4.5 7 7.1 5.1c.8.6 1.9.6 2.7 0L21.5 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <a href="mailto:{{ $leverancier->email }}" class="text-blue-600 dark:text-blue-400 hover:underline truncate">{{ $leverancier->email }}</a>
                                        </p>
                                        <p class="flex items-center gap-2 min-w-0">
                                            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4.5 5.5c0 8 6 14 14 14l2-2a2 2 0 0 0 0-2.8l-1.4-1.4a2 2 0 0 0-2.6-.2l-1.4 1a13.1 13.1 0 0 1-5.2-5.2l1-1.4a2 2 0 0 0-.2-2.6L9.3 4.5a2 2 0 0 0-2.8 0l-2 1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <a href="tel:{{ $leverancier->telefoon }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $leverancier->telefoon }}</a>
                                        </p>
                                    </div>

                                    @if($leverancier->is_actief)
                                    <div class="flex flex-col sm:flex-row gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <a href="{{ route('leveranciers.editleverancier', $leverancier->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded-md text-xs sm:text-sm font-semibold text-center transition-colors">
                                            Bijwerken
                                        </a>
                                        <form method="POST" action="{{ route('leveranciers.softDeleteleverancier', $leverancier->id) }}" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="w-full bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded-md text-xs sm:text-sm font-semibold transition-colors"
                                                onclick="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?')">
                                                Verwijderen
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                            Inactief - Niet bewerkbaar
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                                    <p class="text-lg">Er zijn momenteel nog geen leveranciers geregistreerd.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Levering Modal -->
            <div id="NewLeveringFormModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 p-4">
                <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl w-full max-w-2xl p-6 relative my-8">
                    <button
                        type="button"
                        onclick="showNewLeveringForm()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-white text-2xl z-10">
                        ✕
                    </button>

                    <h2 class="text-xl sm:text-2xl font-semibold mb-6 text-gray-900 dark:text-gray-100 pr-8">Nieuwe Levering</h2>

                    <form method="POST" action="{{ route('leveranciers.storeLevering') }}" class="space-y-6" id="newLevering">
                        @csrf

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Bedrijf</label>
                            <select name="leverancier_id" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @forelse ($leveranciers as $leverancier)
                                    <option value="{{ $leverancier->id }}">{{ $leverancier->bedrijfsnaam }}</option>
                                @empty
                                    <option value="0">Geen bedrijven geregistreerd</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Leverdatum & tijd</label>
                            <input type="datetime-local" name="leverdatum_tijd" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Eerstvolgende levering</label>
                            <input type="datetime-local" name="eerstvolgende_levering" class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" onclick="showNewLeveringForm()" class="w-full sm:w-auto bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">Annuleren</button>
                            <button type="submit" form="newLevering" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">Opslaan</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>

            <!-- Leveringsoverzicht -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="font-semibold text-lg">Leveringsoverzicht</h2>
                    <button
                        onclick="showNewLeveringForm()"
                        class="w-full sm:w-auto bg-blue-600 text-white dark:bg-blue-700 dark:text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                        + Nieuwe Levering
                    </button>
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr class="text-left">
                                <th class="px-4 sm:px-6 py-3 font-semibold">Leverancier</th>
                                <th class="px-4 sm:px-6 py-3 font-semibold">Contactpersoon</th>
                                <th class="px-4 sm:px-6 py-3 font-semibold">Volgende levering</th>
                                <th class="px-4 sm:px-6 py-3 font-semibold">Status</th>
                                <th class="px-4 sm:px-6 py-3 font-semibold text-right">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($leveringen as $levering)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 sm:px-6 py-4 font-medium">{{ $levering->bedrijfsnaam }}</td>
                                    <td class="px-4 sm:px-6 py-4">{{ $levering->contact_naam }}</td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <?php
                                        $formattedDate = \Carbon\Carbon::parse($levering->eerstvolgende_levering)->format('d-m-Y H:i');
                                        ?>
                                        {{ $formattedDate }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $levering->is_actief ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' }}">
                                            {{ $levering->is_actief ? 'Actief' : 'Inactief' }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right space-x-2">
                                        @if($levering->is_actief)
                                        <a href="{{ route('leveranciers.editlevering', $levering->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Bijwerken</a>
                                        <form method="POST" action="{{ route('leveranciers.softDeletelevering', $levering->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm" onclick="return confirm('Weet je zeker dat je deze levering wilt verwijderen?')">Verwijderen</button>
                                        </form>
                                        @else
                                        <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 sm:px-6 py-4 text-center text-gray-500 dark:text-gray-400 col-span-5">
                                        Er zijn momenteel nog geen leveringen gemaakt.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List View -->
                <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($leveringen as $levering)
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-sm sm:text-base truncate">{{ $levering->bedrijfsnaam }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $levering->contact_naam }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold flex-shrink-0 {{ $levering->is_actief ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' }}">
                                    {{ $levering->is_actief ? 'Actief' : 'Inactief' }}
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <?php
                                $formattedDate = \Carbon\Carbon::parse($levering->eerstvolgende_levering)->format('d-m-Y H:i');
                                ?>
                                Volgende levering: {{ $formattedDate }}
                            </p>
                            @if($levering->is_actief)
                            <div class="flex gap-2 pt-2">
                                <a href="{{ route('leveranciers.editlevering', $levering->id) }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded text-xs font-semibold transition-colors">
                                    Bijwerken
                                </a>
                                <form method="POST" action="{{ route('leveranciers.softDeletelevering', $levering->id) }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white px-3 py-1.5 rounded text-xs font-semibold transition-colors" onclick="return confirm('Weet je zeker dat je deze levering wilt verwijderen?')">
                                        Verwijderen
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            Er zijn momenteel nog geen leveringen gemaakt.
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('modal.js') }}"></script>
</x-app-layout>
