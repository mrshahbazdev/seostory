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
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->string('term')->unique();
            $table->integer('volume')->default(0);
            $table->integer('difficulty')->default(0); // 0-100
            $table->decimal('cpc', 8, 2)->default(0.00);
            $table->json('results')->nullable(); // For related keywords list
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
