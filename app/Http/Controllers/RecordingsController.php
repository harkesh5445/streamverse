<?php

namespace App\Http\Controllers;

use App\Models\Recording;
use Illuminate\Http\Request;

class RecordingsController extends Controller
{
    // ...existing methods...

    public function index()
    {
        $recordings = Recording::latest()->get();
        return view('recordings.index', compact('recordings'));
    }

    public function show($id)
    {
        $recording = Recording::findOrFail($id);
        return view('recordings.show', compact('recording'));
    }

    // ...existing methods...
}