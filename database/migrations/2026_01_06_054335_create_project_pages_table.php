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
            // Project ID (UUID format)
            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');
            // Kis Audit scan ka hissa hai
            $table->foreignId('audit_id')->nullable(); 
            
            $table->string('url');
            $table->string('title')->nullable();
            $table->integer('word_count')->default(0);
            $table->decimal('load_time', 5, 2)->nullable(); // Speed
            $table->string('status')->default('pending'); // pending, audited, failed
            
            $table->json('full_audit_data')->nullable(); // Poora X-ray data
            $table->json('schema_types')->nullable();
            $table->integer('health_score')->default(100);
            
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
