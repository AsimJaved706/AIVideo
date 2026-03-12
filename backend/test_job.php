<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$v = \App\Models\Video::latest()->first();
if ($v) {
    \App\Jobs\GenerateScriptJob::dispatch($v);
    echo "Job Dispatched!\n";
} else {
    echo "No video found.\n";
}
