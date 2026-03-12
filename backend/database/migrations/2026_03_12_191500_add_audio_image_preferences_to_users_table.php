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
            $table->string('voice_model')->default('eleven_multilingual_v2')->after('voice_id');
            $table->string('voice_output_format')->default('mp3_44100_128')->after('voice_model');
            $table->string('fallback_language')->default('en')->after('voice_output_format');
            $table->string('image_lighting_style')->default('natural_daylight')->after('seed_image_style');
            $table->string('image_color_palette')->default('neutral')->after('image_lighting_style');
            $table->string('image_framing')->default('medium_shot')->after('image_color_palette');
            $table->text('image_negative_prompt')->nullable()->after('image_framing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'voice_model',
                'voice_output_format',
                'fallback_language',
                'image_lighting_style',
                'image_color_palette',
                'image_framing',
                'image_negative_prompt',
            ]);
        });
    }
};
