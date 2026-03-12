<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('topic');
            $table->string('niche')->nullable();
            $table->string('language')->default('en');
            $table->integer('video_length')->default(60); // in seconds
            $table->string('voice_style')->nullable();
            $table->string('target_platform')->default('tiktok');
            $table->string('video_style')->nullable();
            $table->string('posting_schedule')->nullable();
            $table->enum('status', ['Draft', 'Generating', 'Rendering', 'Ready', 'Published', 'Failed'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
