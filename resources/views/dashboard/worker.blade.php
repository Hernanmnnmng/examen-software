<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Worker Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- 3x3 Grid of Colored Squares -->
            <div class="grid grid-cols-3 gap-4" style="display: grid; grid-template-columns: repeat(3, 1fr);">
                <!-- Tile 1 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white relative">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">My Tasks</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 2 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-green-500 to-emerald-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Completed</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 3 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">In Progress</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 4 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Submit</h3>
                </a>

                <!-- Tile 5 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Time Log</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0h</p>
                </a>

                <!-- Tile 6 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white relative">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Messages</h3>
                    <span class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
                </a>

                <!-- Tile 7 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-lime-500 to-green-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Schedule</h3>
                </a>

                <!-- Tile 8 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-red-600 to-pink-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Stats</h3>
                </a>

                <!-- Tile 9 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Resources</h3>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
