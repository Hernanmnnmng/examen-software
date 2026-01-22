<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nieuwe Leverancier
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-10">

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
            <form method="POST" action="{{ route('leveranciers.storeLeverancier') }}" class="space-y-6">
                @csrf

                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">Bedrijf</h3>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bedrijfsnaam</label>
                    <input
                        type="text"
                        name="bedrijfsnaam"
                        value="{{ old('bedrijfsnaam') }}"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Bijv. Bakkerij Bart"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">Contact</h3>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contactpersoon</label>
                        <input
                            type="text"
                            name="contact_naam"
                            value="{{ old('contact_naam') }}"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Naam contactpersoon"
                        />
                    </div>
                    <div class="sm:pt-9">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefoon</label>
                        <input
                            type="tel"
                            name="telefoon"
                            value="{{ old('telefoon') }}"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="06..."
                        />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-mail</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="naam@bedrijf.nl"
                        />
                    </div>
                </div>

                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">Adres</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Straat</label>
                            <input
                                type="text"
                                name="straat"
                                value="{{ old('straat') }}"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Huisnummer</label>
                            <input
                                type="text"
                                name="huisnummer"
                                value="{{ old('huisnummer') }}"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Postcode</label>
                            <input
                                type="text"
                                name="postcode"
                                value="{{ old('postcode') }}"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Plaats</label>
                            <input
                                type="text"
                                name="plaats"
                                value="{{ old('plaats') }}"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('leveranciers.index') }}"
                       class="w-full sm:w-auto text-center bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">
                        Terug
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-md text-sm font-semibold transition-colors">
                        Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

