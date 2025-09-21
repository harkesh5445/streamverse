<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Recorded Sessions') }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            @if(isset($recordings) && count($recordings) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($recordings as $rec)
                        <div class="bg-white rounded-xl shadow p-4 flex flex-col">
                            <video controls class="rounded mb-2 w-full h-48 object-cover bg-black">
                                <source src="{{ asset('storage/uploads/recordings/' . $rec->filename) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg text-gray-800 mb-1">{{ $rec->title ?? 'Untitled Recording' }}</h3>
                                <p class="text-xs text-gray-500 mb-2">{{ $rec->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="flex space-x-2 mt-2">
                                <a href="{{ asset('storage/uploads/recordings/' . $rec->filename) }}" download class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-xs">Download</a>
                                <a href="{{ route('recordings.show', $rec->id) }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-xs">Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500">No recorded sessions found.</div>
            @endif
        </div>
    </div>
</x-app-layout>
