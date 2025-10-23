<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Admin - Job Details
                </h2>
                <p class="text-sm text-gray-600">{{ $job->title }}</p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <!-- Admin Job Controls -->
                @if($job->status !== 'completed')
                <form action="{{ route('admin.job.force-complete', $job) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105"
                            onclick="return confirm('Are you sure you want to force complete this job?')">
                        ✅ Force Complete
                    </button>
                </form>
                @endif
                
                @if($job->status !== 'cancelled')
                <form action="{{ route('admin.job.force-cancel', $job) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-300 transform hover:scale-105"
                            onclick="return confirm('Are you sure you want to force cancel this job?')">
                        ❌ Force Cancel
                    </button>
                </form>
                @endif
                
                <!-- View Chat -->
                @if($job->accepted_worker_id)
                <a href="{{ route('admin.chat.view', $job) }}" 
                   class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-300 transform hover:scale-105">
                    💬 View Chat
                </a>
                @endif
                
                <!-- Back Button -->
                <a href="{{ route('admin.jobs') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Back to Jobs
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Job Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Job Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Title:</span>
                            <p class="font-semibold">{{ $job->title }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <p class="font-semibold">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($job->status === 'completed') bg-green-100 text-green-800
                                    @elseif($job->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Price:</span>
                            <p class="font-semibold">Tsh {{ number_format($job->amount) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Category:</span>
                            <p class="font-semibold">{{ $job->category->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Posted by:</span>
                            <p class="font-semibold">
                                <a href="{{ route('admin.user.details', $job->muhitaji) }}" class="text-blue-600 hover:underline">
                                    {{ $job->muhitaji->name }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Assigned to:</span>
                            <p class="font-semibold">
                                @if($job->acceptedWorker)
                                    <a href="{{ route('admin.user.details', $job->acceptedWorker) }}" class="text-blue-600 hover:underline">
                                        {{ $job->acceptedWorker->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">Not assigned</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">Description:</span>
                            <p class="mt-2">{{ $job->description ?? 'No description' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <p>{{ $job->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($job->completed_at)
                        <div>
                            <span class="text-gray-600">Completed:</span>
                            <p>{{ $job->completed_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Private Messages -->
            @if($job->accepted_worker_id && $job->privateMessages->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Private Messages ({{ $job->privateMessages->count() }})</h3>
                        <a href="{{ route('admin.chat.view', $job) }}" class="text-blue-600 hover:underline">
                            View Full Chat →
                        </a>
                    </div>
                    
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($job->privateMessages->take(10) as $message)
                            <div class="border-l-4 {{ $message->sender_id === $job->user_id ? 'border-blue-400 bg-blue-50' : 'border-green-400 bg-green-50' }} p-3 rounded">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="font-semibold text-sm">{{ $message->sender->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">{{ $message->created_at->diffForHumans() }}</span>
                                        <p class="text-sm mt-1">{{ Str::limit($message->message, 150) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Public Comments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Public Comments & Applications ({{ $job->comments->count() }})</h3>
                    
                    @if($job->comments->isEmpty())
                        <p class="text-gray-500">No comments yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($job->comments as $comment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="font-semibold">{{ $comment->user->name }}</span>
                                                @if($comment->is_application)
                                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">Application</span>
                                                @endif
                                            </div>
                                            <p class="text-gray-700">{{ $comment->message }}</p>
                                            @if($comment->bid_amount)
                                                <p class="text-sm text-blue-600 mt-2">Bid: Tsh {{ number_format($comment->bid_amount) }}</p>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            @if($job->payment)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Amount:</span>
                            <p class="font-semibold">Tsh {{ number_format($job->payment->amount) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <p class="font-semibold">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $job->payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($job->payment->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600">Reference:</span>
                            <p>{{ $job->payment->reference ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <p>{{ $job->payment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

