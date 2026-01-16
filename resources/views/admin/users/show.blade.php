<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">User Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                            {{ $user->role === 'worker' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                            {{ $user->role === 'user' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($user->email_verified_at)
                                            <span class="text-green-600 dark:text-green-400">Verified on {{ $user->email_verified_at->format('M d, Y') }}</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400">Not verified</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Account Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">#{{ $user->id }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($user->id !== auth()->id())
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete User
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
