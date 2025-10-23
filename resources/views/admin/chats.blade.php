@extends('layouts.app')
@section('title', 'Admin - Monitor All Chats')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Private Conversations</h3>
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                            ← Back to Dashboard
                        </a>
                    </div>

                    @if($conversations->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations yet</h3>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($conversations as $conv)
                                @if($conv->job)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $conv->job->title }}</h4>
                                            <div class="mt-2 text-sm text-gray-600">
                                                <div class="flex items-center gap-4">
                                                    <div>
                                                        <span class="font-medium">Muhitaji:</span> {{ $conv->job->muhitaji->name }}
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">Mfanyakazi:</span> {{ $conv->job->acceptedWorker->name ?? 'Not assigned' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                                                <span>{{ $conv->message_count }} messages</span>
                                                <span>Last: {{ \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.chat.view', $conv->job) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                                View Chat
                                            </a>
                                            <a href="{{ route('admin.job.details', $conv->job) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                                View Job
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $conversations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

