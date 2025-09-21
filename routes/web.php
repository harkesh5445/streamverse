<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StreamController;
use Illuminate\Support\Facades\Route;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $streams = auth()->user()->streams()->get();
    $videos = auth()->user()->videoClips()->get();
    $scheduled = auth()->user()->scheduledStreams()->latest()->get();
    return view('dashboard', compact('streams', 'videos', 'scheduled'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/stream/create', [StreamController::class, 'create'])->name('stream.create');
    Route::post('/stream', [StreamController::class, 'store'])->name('stream.store');
    Route::get('/stream/{stream:uuid}', [StreamController::class, 'show'])->name('stream.show');
    Route::post('/stream/broadcast/{stream:uuid}', [StreamController::class, 'broadcastSignal'])->name('stream.broadcast');

    // Video Clip Routes
    Route::get('/videos', [App\Http\Controllers\VideoClipController::class, 'index'])->name('videos.index');
    Route::get('/videos/upload', [App\Http\Controllers\VideoClipController::class, 'create'])->name('videos.create');
    Route::post('/videos', [App\Http\Controllers\VideoClipController::class, 'store'])->name('videos.store');
    Route::get('/videos/{videoClip}', [App\Http\Controllers\VideoClipController::class, 'show'])->name('videos.show');

    // Scheduled Stream Routes
    Route::get('/schedule', [App\Http\Controllers\ScheduledStreamController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [App\Http\Controllers\ScheduledStreamController::class, 'store'])->name('schedule.store');
    Route::get('/scheduled', [App\Http\Controllers\ScheduledStreamController::class, 'index'])->name('schedule.index');

    Route::get('/recordings', [\App\Http\Controllers\RecordingsController::class, 'index'])->name('recordings.index');
    Route::get('/recordings/{id}', [\App\Http\Controllers\RecordingsController::class, 'show'])->name('recordings.show');

    Route::post('/recordings/upload', function (Request $request) {
        if ($request->hasFile('recording')) {
            $file = $request->file('recording');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/recordings', $filename);
            // Create DB record
            $recording = \App\Models\Recording::create([
                'title' => $request->input('title') ?? pathinfo($filename, PATHINFO_FILENAME),
                'filename' => $filename,
                'description' => $request->input('description'),
                'user_id' => auth()->id(),
            ]);
            return response()->json(['status' => 'saved', 'file' => $filename, 'id' => $recording->id]);
        }
        return response()->json(['status' => 'error'], 400);
    })->middleware(['auth']);
});

require __DIR__.'/auth.php';