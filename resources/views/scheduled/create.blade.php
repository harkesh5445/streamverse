<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Schedule Pre-Recorded Stream') }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto">
            <div class="bg-white rounded-xl shadow p-8">
                <form method="POST" action="{{ route('schedule.store') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Select Video Clip</label>
                        <select name="video_clip_id" class="w-full p-3 border rounded focus:ring focus:ring-purple-200" required>
                            <option value="">-- Select --</option>
                            @foreach($videos as $video)
                                <option value="{{ $video->id }}">{{ $video->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Schedule Time</label>
                        <input type="datetime-local" name="scheduled_at" class="w-full p-3 border rounded focus:ring focus:ring-purple-200" required>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-semibold">Schedule</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
