<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The clinic-owned patient relationship. A single Dental Connect login
     * identity (users) may have many clinic_patients rows — one per clinic
     * they enroll with. Clinic A can never see Clinic B's row for the same
     * human; every clinic-scoped query filters by clinic_id server-side.
     */
    public function up(): void
    {
        Schema::create('clinic_patients', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('patient_number', 40);
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();

            $table->foreignId('assigned_dentist_id')->nullable()->constrained('dentists')->nullOnDelete();
            $table->string('status', 20)->default('active'); // active, inactive
            $table->text('internal_notes')->nullable();

            $table->timestamps();

            $table->unique(['clinic_id', 'user_id']);
            $table->unique(['clinic_id', 'patient_number']);
            $table->index(['clinic_id', 'status', 'created_at']);
            $table->index(['clinic_id', 'status', 'last_name', 'first_name']);
            $table->index('user_id');
        });

        Schema::create('patient_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_patient_id')->constrained()->cascadeOnDelete();
            $table->string('type', 60);
            $table->string('policy_version', 20);
            $table->timestamp('accepted_at');
            $table->timestamps();

            $table->index(['clinic_patient_id', 'type', 'created_at']);
        });

        Schema::create('patient_clinic_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_saved')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'clinic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_clinic_preferences');
        Schema::dropIfExists('patient_consents');
        Schema::dropIfExists('clinic_patients');
    }
};
