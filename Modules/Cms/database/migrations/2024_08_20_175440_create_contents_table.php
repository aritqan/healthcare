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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('sub_type')->nullable();
            $table->text('link')->nullable();
            $table->json('custom_properties')->nullable();
            $table->boolean('can_be_deleted')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->disableable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
