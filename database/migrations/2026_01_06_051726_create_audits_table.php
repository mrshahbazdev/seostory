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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            // Kyunki projects table ki ID UUID hai, isliye yahan foreignUuid use karein
            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');
            
            $table->string('type')->default('self'); // self ya competitor
            $table->foreignUuid('competitor_id')->nullable(); // Agar competitor ka audit ho
            
            $table->integer('overall_health_score')->default(0);
            $table->integer('pages_scanned')->default(0);
            $table->integer('critical_issues')->default(0);
            $table->string('status')->default('pending'); // pending, crawling, completed
            $table->json('summary_data')->nullable();
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
