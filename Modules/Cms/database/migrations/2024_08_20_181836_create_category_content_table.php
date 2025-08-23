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
        Schema::create('category_content', function (Blueprint $table) {
            $table->foreignId('content_category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('relation_type', ['main_category', 'subcategory'])->default('main_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_content');
    }
};
