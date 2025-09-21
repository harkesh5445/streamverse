<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a New Stream') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('stream.store') }}">
                        @csrf
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Stream Title</label>
                            <input id="title" class="block mt-1 w-full" type="text" name="title" required autofocus />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-md">
                                Create Stream
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>