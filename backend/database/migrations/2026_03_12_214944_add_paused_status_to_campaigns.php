<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to change column type differently
        // For MySQL, we can use raw SQL to modify enum
        if (config('database.default') === 'sqlite') {
            // SQLite doesn't enforce enum, status is stored as string
            // No migration needed
        } else {
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('Draft', 'Generating', 'Rendering', 'Ready', 'Published', 'Failed', 'Paused') DEFAULT 'Draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'sqlite') {
            // No changes needed
        } else {
            DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM('Draft', 'Generating', 'Rendering', 'Ready', 'Published', 'Failed') DEFAULT 'Draft'");
        }
    }
};
