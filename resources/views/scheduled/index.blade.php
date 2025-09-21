<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Scheduled Streams') }}
        </h2>
    </x-slot>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Upcoming & Live Streams</h3>
                    <a href="{{ route('schedule.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-sm">+ Schedule</a>
                </div>
                @if(count($scheduled) > 0)
                    <ul class="space-y-4">
                        @foreach($scheduled as $item)
                            <li class="bg-gray-100 rounded-lg p-4 flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <span class="font-semibold text-gray-700">{{ $item->videoClip->title ?? 'N/A' }}</span>
                                    <span class="ml-2 text-gray-500 text-sm">{{ \Carbon\Carbon::parse($item->scheduled_at)->format('M d, Y H:i') }}</span>
                                </div>
                                <span class="mt-2 md:mt-0 text-xs px-2 py-1 rounded {{ $item->status === 'live' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No scheduled streams yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
