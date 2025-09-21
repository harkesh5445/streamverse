<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledStream;
use Carbon\Carbon;

class ActivateScheduledStreams extends Command
{
    protected $signature = 'streams:activate-scheduled';
    protected $description = 'Activate scheduled streams whose time has arrived';

    public function handle()
    {
        $now = Carbon::now();
        $streams = ScheduledStream::where('status', 'pending')
            ->where('scheduled_at', '<=', $now)
            ->get();

        foreach ($streams as $stream) {
            $stream->status = 'live';
            $stream->save();
            $this->info('Activated scheduled stream ID: ' . $stream->id);
        }
    }
}
