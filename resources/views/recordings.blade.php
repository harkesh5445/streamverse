@extends('layouts.app')

@section('content')
<div class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Recorded Live Streams</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($recordings as $rec)
                <div class="bg-white rounded shadow p-4 flex flex-col">
                    <h3 class="font-semibold text-lg mb-2">{{ $rec->title ?? 'Untitled' }}</h3>
                    <video controls class="w-full rounded mb-2">
                        <source src="{{ asset('storage/recordings/' . $rec->filename) }}" type="video/webm">
                        Your browser does not support the video tag.
                    </video>
                    <a href="{{ asset('storage/recordings/' . $rec->filename) }}" download class="text-blue-600 hover:underline text-sm">Download</a>
                </div>
            @empty
                <p class="text-gray-500 col-span-2">No recorded streams found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
