<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- 3x3 Grid of Colored Squares -->
            <div class="grid grid-cols-3 gap-4" style="display: grid; grid-template-columns: repeat(3, 1fr);">
                <!-- Tile 1 -->
                <a href="{{ route('admin.users.index') }}" class="aspect-square bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Users</h3>
                    <p class="text-sm text-center opacity-90 mt-1">{{ \App\Models\User::count() }}</p>
                </a>

                <!-- Tile 2 -->
                <a href="{{ route('admin.users.index', ['role' => 'worker']) }}" class="aspect-square bg-gradient-to-br from-green-500 to-emerald-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Workers</h3>
                    <p class="text-sm text-center opacity-90 mt-1">{{ \App\Models\User::where('role', 'worker')->count() }}</p>
                </a>

                <!-- Tile 3 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Settings</h3>
                </a>

                <!-- Tile 4 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Reports</h3>
                </a>

                <!-- Tile 5 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Tasks</h3>
                    <p class="text-sm text-center opacity-90 mt-1">0</p>
                </a>

                <!-- Tile 6 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white relative">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Alerts</h3>
                    <span class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
                </a>

                <!-- Tile 7 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-lime-500 to-green-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Activity</h3>
                </a>

                <!-- Tile 8 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-slate-700 to-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Security</h3>
                </a>

                <!-- Tile 9 -->
                <a href="#" class="aspect-square bg-gradient-to-br from-amber-500 to-yellow-600 rounded-xl p-6 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex flex-col items-center justify-center text-white">
                    <svg class="h-20 w-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    <h3 class="text-lg font-bold text-center">Database</h3>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
