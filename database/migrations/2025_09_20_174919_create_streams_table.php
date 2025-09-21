<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // For public-facing URLs
            $table->string('title'); // <-- Add this line
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The host
            $table->string('status')->default('pending'); // pending, live, ended
            $table->string('video_path')->nullable(); // For uploaded video clips
            $table->timestamp('scheduled_at')->nullable(); // For pre-recorded streams
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streams');
    }
};
