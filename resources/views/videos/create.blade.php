<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Upload Video Clip') }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-8">
                <form method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Title</label>
                        <input type="text" name="title" class="w-full p-3 border rounded focus:ring focus:ring-blue-200" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Video File (mp4, webm, ogg, max 50MB)</label>
                        <input type="file" name="video" class="w-full p-3 border rounded focus:ring focus:ring-blue-200" required>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">Upload</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
