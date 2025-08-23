<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Modules\Config\Enums\SettingTypes;
use Modules\Config\Enums\SettingGroups;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->enum('group', SettingGroups::all())->nullable();
            $table->enum('type', SettingTypes::all())->default(SettingTypes::TEXT)->nullable();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->json('options')->nullable()->default(null);
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->boolean('translatable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
