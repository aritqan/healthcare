<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Models\Admin;
use Modules\Cms\Models\Content;
use Modules\Nabd\Enums\MedicalFacilitesTypes;
use Modules\Nabd\Models\MedicalFacility;
use Modules\Zms\Models\State;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(State::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(MedicalFacility::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Content::class, 'medical_specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', MedicalFacilitesTypes::values())->default(MedicalFacilitesTypes::CLINIC);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_facilities');
    }
};
