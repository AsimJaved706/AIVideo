<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Campaign;
use App\Jobs\GenerateScriptJob;

class MockCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:mock {topic}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mock a campaign generation for E2E testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $topic = $this->argument('topic');

        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password')
        ]);

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'title' => "Test Campaign: $topic",
            'topic' => $topic,
            'status' => 'Generating'
        ]);

        $video = $campaign->videos()->create(['status' => 'Generating Scripts']);

        $this->info("Created Campaign #{$campaign->id} - {$campaign->title}");
        $this->info("Dispatching GenerateScriptJob for Video #{$video->id}...");

        GenerateScriptJob::dispatch($video);

        $this->info("Job Dispatched! Check horizon/queue logs.");
    }
}
