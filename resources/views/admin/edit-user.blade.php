@extends('layouts.app')

@section('title', 'Edit User - Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-red-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        🛠️ Edit User: {{ $user->name }}
                    </h1>
                    <p class="text-gray-600">Full control over user account and settings</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.user.details', $user) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        👁️ View Details
                    </a>
                    <a href="{{ route('admin.users') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        ← Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Full Name *
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            User Role *
                        </label>
                        <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('role') border-red-500 @enderror">
                            <option value="muhitaji" {{ old('role', $user->role) == 'muhitaji' ? 'selected' : '' }}>
                                Muhitaji (Job Poster)
                            </option>
                            <option value="mfanyakazi" {{ old('role', $user->role) == 'mfanyakazi' ? 'selected' : '' }}>
                                Mfanyakazi (Worker)
                            </option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="number" step="any" name="lat" value="{{ old('lat', $user->lat) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('lat') border-red-500 @enderror">
                        @error('lat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="number" step="any" name="lng" value="{{ old('lng', $user->lng) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent @error('lng') border-red-500 @enderror">
                        @error('lng')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Account Status</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Account is Active
                            </label>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $user->is_active ? '✅ Active' : '❌ Suspended' }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.user.details', $user) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        💾 Update User
                    </button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-50 border border-red-200 rounded-2xl p-8 mt-8">
            <h3 class="text-xl font-bold text-red-800 mb-4">⚠️ Danger Zone</h3>
            <p class="text-red-700 mb-6">These actions are irreversible. Please be careful.</p>
            
            <div class="flex space-x-4">
                <form action="{{ route('admin.user.toggle-status', $user) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        {{ $user->is_active ? '🚫 Suspend User' : '✅ Activate User' }}
                    </button>
                </form>
                
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.user.delete', $user) }}" method="POST" class="inline" 
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        🗑️ Delete User
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
