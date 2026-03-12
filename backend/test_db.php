<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
echo "Gemini: " . (!empty($user->gemini_api_key) ? "YES" : "NO") . "\n";
echo "ElevenLabs: " . (!empty($user->elevenlabs_api_key) ? "YES" : "NO") . "\n";
echo "Pexels: " . (!empty($user->pexels_api_key) ? "YES" : "NO") . "\n";
echo "RunwayML: " . (!empty($user->runwayml_api_key) ? "YES" : "NO") . "\n";
