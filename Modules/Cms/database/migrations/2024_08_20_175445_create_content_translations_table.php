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
        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();

            $table->unique(['content_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_translations');
    }
};
