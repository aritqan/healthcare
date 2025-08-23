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
        Schema::create('notification_template_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_template_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->name('nt_template_id_fk');
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('short_template')->nullable();
            $table->text('long_template')->nullable();

            $table->unique(['notification_template_id', 'locale'], 'nt_template_id_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_template_translations');
    }
};
