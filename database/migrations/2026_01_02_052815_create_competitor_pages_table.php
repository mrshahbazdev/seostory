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
        Schema::create('competitor_pages', function (Blueprint $table) {
            $table->id();
            
            $table->foreignUuid('competitor_id')->constrained()->onDelete('cascade');
            
            $table->string('url');
            $table->longText('raw_content')->nullable();
            $table->json('metadata')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitor_pages');
    }
};
