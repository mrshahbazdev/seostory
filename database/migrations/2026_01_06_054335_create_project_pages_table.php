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
        Schema::create('project_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('audit_id')->nullable();
            $table->string('url');
            $table->string('title')->nullable();
            $table->integer('word_count')->default(0);
            $table->string('status')->default('pending');
            $table->integer('health_score')->default(100);
            
            // Advanced Columns yahan honay chahiyen:
            $table->decimal('load_time', 5, 2)->nullable();
            $table->json('full_audit_data')->nullable();
            $table->json('schema_types')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_pages');
    }
};
