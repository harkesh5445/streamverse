<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\StreamSignalEvent;
use Illuminate\Support\Facades\Gate;

class StreamController extends Controller
{
    public function create()
    {
        return view('stream.create');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $stream = auth()->user()->streams()->create([
            'title' => $request->input('title'),
            'status' => 'live', // Set status to live immediately
        ]);

        return redirect()->route('stream.show', ['stream' => $stream->uuid]);
    }

    public function show(Stream $stream)
    {
        Gate::authorize('view', $stream);

        return view('stream.show', [
            'stream' => $stream,
            'currentUser' => auth()->user(),
        ]);
    }

    public function broadcastSignal(Request $request, Stream $stream)
    {
        Gate::authorize('broadcast-signal', $stream);
        
        $data = $request->input('data');
        $senderId = auth()->id();

        event(new StreamSignalEvent($data, $stream->uuid, $senderId));

        return response()->json(['status' => 'signal broadcasted']);
    }
}