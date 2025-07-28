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
        Schema::create('onedg_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onedg_campaign_config_id')->constrained('onedg_campaign_configs')->cascadeOnDelete();
            $table->string('channel_link', 1024);
            $table->timestamp('expires_at')->nullable();
            $table->string('status')->default('active');
            $table->json('processed_videos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onedg_campaigns');
    }
};
