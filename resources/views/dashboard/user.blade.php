<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- 3x3 Grid of Colored Squares -->
            <div class="grid grid-cols-3 gap-4" style="display: grid; grid-template-columns: repeat(3, 1fr);">
                <!-- Tile 1 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">New Request</h3>
                </a>

                <!-- Tile 2 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-green-500 to-emerald-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">My Requests</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 3 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Active</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 4 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Completed</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 5 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white relative">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Messages</h3>
                    <span class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
                </a>

                <!-- Tile 6 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">History</h3>
                </a>

                <!-- Tile 7 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-lime-500 to-green-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white relative">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Alerts</h3>
                    <span class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
                </a>

                <!-- Tile 8 -->
                <a href="{{ route('profile.edit') }}" class="aspect-square bg-gradient-to-br from-red-600 to-pink-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Profile</h3>
                </a>

                <!-- Tile 9 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Help</h3>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
