<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Video Clip: ' . $videoClip->title) }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-8 flex flex-col items-center">
                <h3 class="text-xl font-bold mb-4">{{ $videoClip->title }}</h3>
                <video controls class="w-full rounded mb-4" src="{{ asset('storage/' . $videoClip->file_path) }}"></video>
                <a href="{{ route('videos.index') }}" class="text-blue-600 hover:underline">&larr; Back to list</a>
            </div>
        </div>
    </div>
</x-app-layout>
