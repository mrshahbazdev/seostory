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
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->integer('overall_health_score')->default(0);
            $table->integer('pages_scanned')->default(0);
            $table->integer('critical_issues')->default(0);
            $table->json('summary_data')->nullable(); // Comparison ke liye data
            $table->timestamps(); // Is se pata chalega scan kab hua
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
