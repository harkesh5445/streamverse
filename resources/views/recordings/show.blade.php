<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ $recording->title ?? 'Untitled Recording' }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">
            <video controls class="rounded w-full h-64 object-cover bg-black mb-4">
                <source src="{{ asset('storage/uploads/recordings/' . $recording->filename) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="mb-2 text-gray-700">
                <span class="font-semibold">Date:</span> {{ $recording->created_at->format('M d, Y H:i') }}
            </div>
            <div class="mb-4 text-gray-700">
                <span class="font-semibold">Description:</span> {{ $recording->description ?? 'No description provided.' }}
            </div>
            <a href="{{ asset('storage/uploads/recordings/' . $recording->filename) }}" download class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Download Recording</a>
            <a href="{{ route('recordings.index') }}" class="ml-4 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Back to Recordings</a>
        </div>
    </div>
</x-app-layout>
