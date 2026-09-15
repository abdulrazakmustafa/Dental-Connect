<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clinic_patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('moderation_status', 20)->default('published'); // published, hidden, flagged
            $table->timestamps();

            $table->unique(['appointment_id', 'clinic_patient_id']);
            $table->index(['clinic_id', 'moderation_status', 'created_at']);
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('requester_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('category', 60);
            $table->string('subject', 160);
            $table->text('description');
            $table->string('related_type', 120)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();

            $table->string('status', 20)->default('open'); // open, investigating, resolved, closed
            $table->string('priority', 10)->default('normal'); // low, normal, high
            $table->text('internal_notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'priority', 'updated_at']);
            $table->index('requester_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('reviews');
    }
};
