<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Admin - Monitor User Activity
                </h2>
                <p class="text-sm text-gray-600">{{ $user->name }} ({{ $user->email }})</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.user.details', $user) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    View Profile
                </a>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    ← Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6">Activity Timeline</h3>
                    
                    @if($activities->isEmpty())
                        <p class="text-gray-500">No recent activity.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($activities as $activity)
                                @if($activity['type'] === 'job')
                                    <div class="flex gap-4 border-l-4 border-blue-400 pl-4 py-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-xl">📋</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-semibold">{{ $activity['data']->title }}</h4>
                                                <span class="text-xs text-gray-500">{{ $activity['timestamp']->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Job {{ $activity['data']->user_id === $user->id ? 'posted' : 'assigned' }} • 
                                                Status: <span class="font-medium">{{ $activity['data']->status }}</span>
                                            </p>
                                            <a href="{{ route('admin.job.details', $activity['data']) }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">
                                                View Job →
                                            </a>
                                        </div>
                                    </div>
                                @elseif($activity['type'] === 'message')
                                    <div class="flex gap-4 border-l-4 border-green-400 pl-4 py-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-xl">💬</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-semibold">Private Message</h4>
                                                <span class="text-xs text-gray-500">{{ $activity['timestamp']->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $activity['data']->sender_id === $user->id ? 'Sent to' : 'Received from' }}
                                                <span class="font-medium">{{ $activity['data']->sender_id === $user->id ? $activity['data']->receiver->name : $activity['data']->sender->name }}</span>
                                            </p>
                                            <p class="text-sm text-gray-700 mt-2 p-2 bg-gray-50 rounded">{{ Str::limit($activity['data']->message, 100) }}</p>
                                            @if($activity['data']->job)
                                                <a href="{{ route('admin.chat.view', $activity['data']->job) }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">
                                                    View Full Chat →
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($activity['type'] === 'transaction')
                                    <div class="flex gap-4 border-l-4 border-yellow-400 pl-4 py-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <span class="text-xl">💰</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-semibold">{{ ucfirst($activity['data']->type) }} Transaction</h4>
                                                <span class="text-xs text-gray-500">{{ $activity['timestamp']->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Amount: <span class="font-medium text-{{ $activity['data']->type === 'credit' ? 'green' : 'red' }}-600">
                                                    {{ $activity['data']->type === 'credit' ? '+' : '-' }}Tsh {{ number_format($activity['data']->amount) }}
                                                </span>
                                            </p>
                                            @if($activity['data']->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $activity['data']->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

