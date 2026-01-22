<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $isAdmin ? __('Directie Dashboard') : __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Dashboard Content -->
            @if($isAdmin)
                <!-- Welcome Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <h3 class="text-2xl font-bold">Welkom, {{ auth()->user()->Voornaam }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">Directie Dashboard - Voedselbank Maaskantje</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rapportages Tabs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <!-- Tab Navigation -->
                        <div class="flex gap-4 mb-6 border-b border-gray-200 dark:border-gray-700">
                            <form method="GET" action="{{ route('dashboard') }}" class="inline">
                                <input type="hidden" name="rapportage" value="productcategorie">
                                <input type="hidden" name="maand" value="{{ $maand }}">
                                <input type="hidden" name="jaar" value="{{ $jaar }}">
                                <button type="submit"
                                    class="px-4 py-2 font-medium border-b-2 transition {{ $rapportageType === 'productcategorie' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                                    Maandoverzicht per Productcategorie
                                </button>
                            </form>
                            <form method="GET" action="{{ route('dashboard') }}" class="inline">
                                <input type="hidden" name="rapportage" value="postcode">
                                <input type="hidden" name="maand" value="{{ $maand }}">
                                <input type="hidden" name="jaar" value="{{ $jaar }}">
                                <button type="submit"
                                    class="px-4 py-2 font-medium border-b-2 transition {{ $rapportageType === 'postcode' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                                    Maandoverzicht per Postcode
                                </button>
                            </form>
                        </div>

                        <!-- Filter Form -->
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Filter Rapportage</h3>
                        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-end mb-6">
                            <input type="hidden" name="rapportage" value="{{ $rapportageType ?? 'productcategorie' }}">

                            <div class="flex-1 min-w-[200px]">
                                <label for="maand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Maand
                                </label>
                                <select name="maand" id="maand" required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500">
                                    <option value="">Selecteer maand</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $maand == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex-1 min-w-[200px]">
                                <label for="jaar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Jaar
                                </label>
                                <select name="jaar" id="jaar" required
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500">
                                    <option value="">Selecteer jaar</option>
                                    @for($y = date('Y'); $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $jaar == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <button type="submit"
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition">
                                    Genereer Rapportage
                                </button>
                            </div>
                        </form>

                        <!-- Results -->
                        @if($rapportageType && $maand && $jaar)
                            <div class="mt-6">
                                <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                    Resultaten voor {{ DateTime::createFromFormat('!m', $maand)->format('F') }} {{ $jaar }}
                                </h4>

                                @if(count($rapportageData) > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-900">
                                                <tr>
                                                    @if($rapportageType === 'productcategorie')
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Productcategorie
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Product
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Leverancier
                                                        </th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Aantal
                                                        </th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Totaal Producten
                                                        </th>
                                                    @else
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Postcode
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Stad
                                                        </th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Productcategorie
                                                        </th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Aantal Pakketten
                                                        </th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                            Totaal Producten
                                                        </th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($rapportageData as $row)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                        @if($rapportageType === 'productcategorie')
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                {{ $row->Productcategorie }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                {{ $row->Product }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                {{ $row->Leverancier }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                                {{ $row->Aantal }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                                {{ $row->TotaalProducten }}
                                                            </td>
                                                        @else
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $row->Postcode }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                {{ $row->Stad }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                {{ $row->Productcategorie }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                                {{ $row->AantalVoedselpakketten }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                                {{ $row->TotaalProducten }}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Geen data</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Er zijn geen resultaten gevonden voor de geselecteerde periode.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <p class="text-blue-800 dark:text-blue-300">
                                    Selecteer een maand en jaar om een rapportage te genereren.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Snelle Links</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('leveranciers.index') }}" class="flex items-center gap-2 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Leveranciers Beheren
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Gebruikers Beheren
                            </a>
                            <a href="{{ route('voedselpakketten.index') }}" class="flex items-center gap-2 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Voedselpakketten
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Regular User Dashboard -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center gap-4">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            <div>
                                <h3 class="text-2xl font-bold">Welkom, {{ auth()->user()->Voornaam }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">Voedselbank Maaskantje</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dashboard Content -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Onze Missie</p>
                                    <p class="text-gray-900 dark:text-gray-100 mt-2">Voedsel voor iedereen in nood</p>
                                </div>
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Wat We Doen</p>
                                    <p class="text-gray-900 dark:text-gray-100 mt-2">Voedselpakketten verspreiden</p>
                                </div>
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Contact</p>
                                    <p class="text-gray-900 dark:text-gray-100 mt-2">Neem contact met ons op</p>
                                </div>
                                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Over Dit Systeem</h3>
                        <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Beheer van voedselpakketten
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Registratie van klanten
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Voorraadbeheer
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
