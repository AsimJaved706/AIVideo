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
        Schema::table('users', function (Blueprint $table) {
            $table->string('voice_id')->nullable()->after('runwayml_api_key');
            $table->decimal('voice_stability', 3, 2)->default(0.35)->after('voice_id');
            $table->decimal('voice_similarity_boost', 3, 2)->default(0.80)->after('voice_stability');
            $table->decimal('voice_style', 3, 2)->default(0.15)->after('voice_similarity_boost');
            $table->boolean('voice_speaker_boost')->default(true)->after('voice_style');
            $table->string('script_tone')->default('natural')->after('voice_speaker_boost');
            $table->string('visual_style')->default('documentary')->after('script_tone');
            $table->string('seed_image_style')->default('photorealistic')->after('visual_style');
            $table->string('camera_motion')->default('gentle_handheld')->after('seed_image_style');
            $table->unsignedTinyInteger('runway_duration')->default(5)->after('camera_motion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'voice_id',
                'voice_stability',
                'voice_similarity_boost',
                'voice_style',
                'voice_speaker_boost',
                'script_tone',
                'visual_style',
                'seed_image_style',
                'camera_motion',
                'runway_duration',
            ]);
        });
    }
};
