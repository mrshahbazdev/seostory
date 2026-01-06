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
        Schema::table('project_pages', function (Blueprint $table) {
            $table->decimal('load_time', 5, 2)->nullable(); // Site speed
            $table->json('schema_types')->nullable(); // FAQ, Article, Product schemas
            $table->json('vitals')->nullable(); // LCP, FID, CLS data
            $table->string('canonical_url')->nullable();
            $table->integer('internal_links_count')->default(0);
            $table->longText('full_audit_data')->nullable(); // Sara raw JSON yahan jayega
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
