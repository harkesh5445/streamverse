<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledStream;
use App\Models\VideoClip;
use Illuminate\Support\Facades\Auth;

class ScheduledStreamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scheduled = ScheduledStream::where('user_id', Auth::id())->latest()->get();
        return view('scheduled.index', compact('scheduled'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $videos = VideoClip::where('user_id', Auth::id())->get();
        return view('scheduled.create', compact('videos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_clip_id' => 'required|exists:video_clips,id',
            'scheduled_at' => 'required|date|after:now',
        ]);

        ScheduledStream::create([
            'user_id' => Auth::id(),
            'video_clip_id' => $request->video_clip_id,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'pending',
        ]);

        return redirect()->route('schedule.index')->with('success', 'Stream scheduled!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
