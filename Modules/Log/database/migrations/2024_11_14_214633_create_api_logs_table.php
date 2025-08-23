<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Log\Enums\ApiLogStatuses;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('user');
            $table->string('service_name');
            $table->string('method');
            $table->string('endpoint');
            $table->json('request');
            $table->json('response');
            $table->enum('status', ApiLogStatuses::all())->default(ApiLogStatuses::SUCCESS);
            $table->integer('status_code')->nullable();
            $table->text('error')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
