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
        Schema::table('documents', function (Blueprint $table) {
            $table->index('org');
            $table->index('rev');
            $table->index(['user_id', 'created_at']); // Composite index for user document listing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['org']);
            $table->dropIndex(['rev']);
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
};
