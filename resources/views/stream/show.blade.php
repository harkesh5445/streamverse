<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Live Stream: ' . $stream->title) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200"
                    data-uuid="{{ $stream->uuid }}"
                    data-user-id="{{ $currentUser->id }}">

                    <h3 class="text-2xl font-bold">{{ $stream->title }}</h3>
                    <p class="text-gray-600">Stream ID: {{ $stream->uuid }}</p>

                    <div id="video-container" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $liveScheduled = $stream->scheduledStreams()->where('status', 'live')->first();
                        @endphp
                        @if($liveScheduled && $liveScheduled->videoClip)
                            <div class="col-span-full">
                                <h4 class="text-lg font-semibold mb-2">Now Playing: {{ $liveScheduled->videoClip->title }}</h4>
                                <video controls autoplay class="w-full rounded" src="{{ asset('storage/' . $liveScheduled->videoClip->file_path) }}"></video>
                            </div>
                        @else
                            <div class="bg-black relative">
                                <video id="local-video" class="w-full h-full object-cover" autoplay muted playsinline></video>
                                <span class="absolute bottom-2 left-2 text-white bg-black bg-opacity-50 px-2 py-1 rounded">You</span>
                            </div>

                            <div id="remote-videos" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <p class="text-center text-gray-500 col-span-full mt-24">Waiting for others to join...</p>
                            </div>
                        @endif
                    </div>

                    <!-- Live Controls Bar -->
                    <div id="live-controls" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 flex items-center bg-white bg-opacity-90 rounded-full shadow-lg px-4 py-2 space-x-4 z-50 border border-gray-200" style="display:none;">
                        <button id="stop-stream-btn" class="hover:bg-red-100 p-2 rounded-full group" title="Stop Live">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="8" fill="currentColor"/></svg>
                            <span class="tooltip group-hover:opacity-100">Stop Live</span>
                        </button>
                        <button id="save-stop-btn" class="hover:bg-blue-100 p-2 rounded-full group" title="Save & Stop">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="tooltip group-hover:opacity-100">Save & Stop</span>
                        </button>
                        <button id="toggle-layout-btn" class="hover:bg-gray-100 p-2 rounded-full group" title="Toggle Layout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="3" y="3" width="7" height="7" stroke-width="2"/><rect x="14" y="3" width="7" height="7" stroke-width="2"/><rect x="14" y="14" width="7" height="7" stroke-width="2"/><rect x="3" y="14" width="7" height="7" stroke-width="2"/></svg>
                            <span class="tooltip group-hover:opacity-100">Toggle Layout</span>
                        </button>
                        <button id="add-member-btn" class="hover:bg-purple-100 p-2 rounded-full group" title="Add Member">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 8c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"/></svg>
                            <span class="tooltip group-hover:opacity-100">Add Member</span>
                        </button>
                        <button id="record-btn" class="hover:bg-red-100 p-2 rounded-full group" title="Start Recording">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="8" fill="currentColor"/></svg>
                            <span class="tooltip group-hover:opacity-100">Start/Stop Recording</span>
                        </button>
                        <span id="live-timer" class="ml-4 text-lg font-mono text-green-700 flex items-center">
                            <span id="record-dot" class="h-3 w-3 bg-red-500 rounded-full mr-2 animate-pulse" style="display:none;"></span>
                            <span id="timer-text">00:00</span>
                        </span>
                    </div>
                    <!-- Start Live Button -->
                    <div class="flex justify-center mt-8">
                        <button id="start-stream-btn" class="p-4 rounded-full bg-green-500 hover:bg-green-600 shadow-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75" title="Start Live">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><polygon points="5,3 19,12 5,21" fill="currentColor"/></svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    @vite('resources/js/webrtc.js')

    <style>
        .tooltip {
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(30, 41, 59, 0.95);
            color: #fff;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 100;
        }
        .group:hover .tooltip {
            opacity: 1;
        }
    </style>
</x-app-layout>