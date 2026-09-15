<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained('dentists')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();

            $table->date('preferred_date');
            $table->time('preferred_time')->nullable();
            $table->text('patient_note')->nullable();
            $table->text('clinic_note')->nullable();

            $table->string('status', 20)->default('requested');
            // requested, confirmed, reschedule_proposed, declined, cancelled, completed, no_show
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['clinic_id', 'status', 'preferred_date']);
            $table->index(['clinic_patient_id', 'status', 'preferred_date']);
            $table->index(['dentist_id', 'preferred_date', 'preferred_time', 'status'], 'appointments_dentist_schedule_index');
            $table->index(['status', 'created_at']);
            $table->index(['clinic_id', 'created_at']);
        });

        Schema::create('appointment_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['appointment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_status_history');
        Schema::dropIfExists('appointments');
    }
};
