<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationStatuses;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', NotificationStatuses::all())->default(NotificationStatuses::PENDING);
            $table->boolean('is_fcm_mobile')->default(false);
            $table->boolean('is_fcm_web')->default(false);
            $table->boolean('is_email')->default(false);
            $table->boolean('is_sms')->default(false);

            $table->index('is_fcm_mobile');
            $table->index('is_fcm_web');
            $table->index('is_email');
            $table->index('is_sms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_channels');
    }
};
