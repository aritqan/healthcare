<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Crm\Enums\ContactusStatuses;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contactuses', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->enum('status', ContactusStatuses::all())->default(ContactusStatuses::PENDING);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('locale', 10)->nullable();
            $table->text('message')->nullable();
            $table->text('reply')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactuses');
    }
};
