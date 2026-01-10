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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            // Project ID (UUID) - Nullable for "Quick Audits"
            $table->foreignUuid('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('url'); // The URL being audited
            $table->string('title')->nullable(); // Page title

            // Audit Meta Info
            $table->string('type')->default('self'); // self ya competitor
            $table->foreignUuid('competitor_id')->nullable();
            $table->string('status')->default('pending'); // pending, processing, completed, failed

            // 📊 Pillar Scores (Seobility Style)
            $table->integer('overall_health_score')->default(0);
            $table->integer('score_tech')->default(0);
            $table->integer('score_structure')->default(0);
            $table->integer('score_content')->default(0);
            $table->integer('score_meta')->default(0); // Added for Meta Analysis

            // 📈 Quantitative Stats
            $table->integer('pages_scanned')->default(0);
            $table->integer('critical_issues')->default(0);

            // 🗄️ Deep Analysis Data (JSON Storage)
            // Isme hum Duplicate titles, Response distributions, aur Link depth details save karenge
            $table->json('tech_meta_data')->nullable();
            $table->json('structure_data')->nullable();
            $table->json('content_data')->nullable();
            $table->json('summary_data')->nullable(); // For general overview

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};