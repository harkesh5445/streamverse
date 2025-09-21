<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('My Video Clips') }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Uploaded Videos</h3>
                    <a href="{{ route('videos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">+ Upload</a>
                </div>
                @if(count($videos) > 0)
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($videos as $video)
                            <li class="bg-gray-100 rounded-lg p-4 flex flex-col items-center">
                                <a href="{{ route('videos.show', $video->id) }}" class="text-blue-700 hover:underline font-semibold text-lg mb-2">{{ $video->title }}</a>
                                <span class="text-xs text-gray-500">ID: {{ $video->id }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No videos uploaded yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
