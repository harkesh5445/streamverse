<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Streams Card -->
            <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Your Streams</h3>
                    <a href="{{ route('stream.create') }}" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">+ New</a>
                </div>
                @if(isset($streams) && count($streams) > 0)
                    <ul class="space-y-2">
                        @foreach($streams as $stream)
                            <li>
                                <a href="{{ route('stream.show', ['stream' => $stream->uuid]) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $stream->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No streams yet.</p>
                @endif
            </div>

            <!-- Video Clips Card -->
            <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Your Video Clips</h3>
                    <a href="{{ route('videos.create') }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">+ Upload</a>
                </div>
                @if(isset($videos) && count($videos) > 0)
                    <ul class="space-y-2">
                        @foreach($videos as $video)
                            <li>
                                <a href="{{ route('videos.show', $video->id) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $video->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No videos uploaded yet.</p>
                @endif
            </div>

            <!-- Scheduled Streams Card -->
            <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Scheduled Streams</h3>
                    <a href="{{ route('schedule.create') }}" class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 text-sm">+ Schedule</a>
                </div>
                @if(isset($scheduled) && count($scheduled) > 0)
                    <ul class="space-y-2">
                        @foreach($scheduled as $item)
                            <li>
                                <span class="font-semibold text-gray-700">{{ $item->videoClip->title ?? 'N/A' }}</span>
                                <span class="ml-2 text-gray-500 text-sm">{{ \Carbon\Carbon::parse($item->scheduled_at)->format('M d, Y H:i') }}</span>
                                <span class="ml-2 text-xs px-2 py-1 rounded {{ $item->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No scheduled streams yet.</p>
                @endif
            </div>

            <!-- Recorded Sessions Card -->
            <div class="bg-white rounded-xl shadow p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Recorded Sessions</h3>
                    <a href="{{ route('recordings.index') }}" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">View All</a>
                </div>
                @if(isset($recordings) && count($recordings) > 0)
                    <ul class="space-y-2">
                        @foreach($recordings as $rec)
                            <li>
                                <a href="{{ route('recordings.show', $rec->id) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $rec->title ?? 'Untitled Recording' }}
                                </a>
                                <span class="ml-2 text-gray-500 text-xs">{{ $rec->created_at->format('M d, Y H:i') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No recorded sessions yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>