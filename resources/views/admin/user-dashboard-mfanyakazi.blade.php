<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Admin View - Mfanyakazi Dashboard
                </h2>
                <p class="text-sm text-gray-600">{{ $user->name }} ({{ $user->email }})</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.user.details', $user) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Full Profile
                </a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-600 text-sm font-medium">Total Jobs Assigned</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_jobs'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-600 text-sm font-medium">Completed Jobs</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['completed_jobs'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-600 text-sm font-medium">Wallet Balance</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">Tsh {{ number_format($stats['wallet_balance']) }}</p>
                </div>
            </div>

            <!-- Assigned Jobs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Assigned Jobs</h3>
                    
                    @if($assignedJobs->isEmpty())
                        <p class="text-gray-500">No jobs assigned yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($assignedJobs as $job)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $job->title }}</h4>
                                            <div class="mt-2 flex items-center gap-4 text-sm text-gray-600">
                                                <span>{{ $job->category->name }}</span>
                                                <span>Tsh {{ number_format($job->amount) }}</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($job->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($job->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                    @else bg-blue-100 text-blue-800
                                                    @endif">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-600">
                                                Client: <a href="{{ route('admin.user.details', $job->muhitaji) }}" class="text-blue-600 hover:underline">{{ $job->muhitaji->name }}</a>
                                            </p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.job.details', $job) }}" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                                View Details
                                            </a>
                                            <a href="{{ route('admin.chat.view', $job) }}" class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">
                                                View Chat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $assignedJobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

