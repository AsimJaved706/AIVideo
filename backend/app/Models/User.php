<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gemini_api_key',
        'elevenlabs_api_key',
        'pexels_api_key',
        'runwayml_api_key',
        'voice_id',
        'voice_model',
        'voice_output_format',
        'fallback_language',
        'voice_stability',
        'voice_similarity_boost',
        'voice_style',
        'voice_speaker_boost',
        'script_tone',
        'visual_style',
        'seed_image_style',
        'image_lighting_style',
        'image_color_palette',
        'image_framing',
        'image_negative_prompt',
        'camera_motion',
        'runway_duration',
        'storage_preference',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'gemini_api_key',
        'elevenlabs_api_key',
        'pexels_api_key',
        'runwayml_api_key',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gemini_api_key' => 'encrypted',
            'elevenlabs_api_key' => 'encrypted',
            'pexels_api_key' => 'encrypted',
            'runwayml_api_key' => 'encrypted',
            'voice_stability' => 'float',
            'voice_similarity_boost' => 'float',
            'voice_style' => 'float',
            'voice_speaker_boost' => 'boolean',
            'runway_duration' => 'integer',
        ];
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function connectedAccounts()
    {
        return $this->hasMany(ConnectedAccount::class);
    }
}
