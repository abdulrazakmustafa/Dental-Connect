<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('slug')->unique();
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('cover_image_path')->nullable();

            $table->string('verification_status', 20)->default('draft');
            // draft, submitted, under_review, changes_requested, approved, rejected, suspended
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('primary_region_id')->nullable();

            $table->unsignedTinyInteger('profile_completion_percent')->default(0);
            $table->timestamps();

            $table->index(['verification_status', 'is_active']);
            $table->index('primary_region_id');
        });

        Schema::create('clinic_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->string('label', 80)->default('Main');
            $table->string('address_line')->nullable();
            $table->string('region', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('area', 80)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();

            $table->index('clinic_id');
            $table->index(['region', 'city', 'area']);
            $table->index(['lat', 'lng']);
        });

        Schema::create('clinic_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['clinic_id', 'service_id']);
            $table->index(['service_id', 'clinic_id']);
        });

        Schema::create('clinic_specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['clinic_id', 'specialty_id']);
            $table->index(['specialty_id', 'clinic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_specialties');
        Schema::dropIfExists('clinic_services');
        Schema::dropIfExists('clinic_locations');
        Schema::dropIfExists('clinics');
    }
};
