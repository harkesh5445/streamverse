<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoClip;
use Illuminate\Support\Facades\Auth;

class VideoClipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = VideoClip::where('user_id', Auth::id())->latest()->get();
        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,webm,ogg|max:51200', // 50MB max
        ]);

        $path = $request->file('video')->store('videos', 'public');

        $video = VideoClip::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'title' => $request->input('title'),
        ]);

        return redirect()->route('videos.show', $video->id)->with('success', 'Video uploaded!');
    }

    /**
     * Display the specified resource.
     */
    public function show(VideoClip $videoClip)
    {
        return view('videos.show', compact('videoClip'));
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
