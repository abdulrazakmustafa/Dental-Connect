<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dentists', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('full_name', 160);
            $table->string('photo_path')->nullable();
            $table->text('bio')->nullable();
            $table->string('license_number', 80)->nullable();
            $table->string('status', 20)->default('active'); // active, inactive
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['clinic_id', 'status', 'sort_order']);
        });

        Schema::create('dentist_specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dentist_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dentist_id', 'specialty_id']);
        });

        Schema::create('clinic_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 0=Sunday..6=Saturday
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->unique(['clinic_id', 'day_of_week']);
        });

        Schema::create('clinic_blackout_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained('dentists')->cascadeOnDelete();
            $table->date('date');
            $table->string('reason', 160)->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_blackout_dates');
        Schema::dropIfExists('clinic_hours');
        Schema::dropIfExists('dentist_specialties');
        Schema::dropIfExists('dentists');
    }
};
