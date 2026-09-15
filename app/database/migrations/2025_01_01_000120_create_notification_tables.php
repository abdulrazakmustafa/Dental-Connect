<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 80); // e.g. appointment.confirmed
            $table->string('title', 160);
            $table->text('body')->nullable();
            $table->string('related_type', 120)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'read_at', 'created_at']);
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained()->cascadeOnDelete();
            $table->string('channel', 20); // email, sms, whatsapp, push
            $table->string('provider', 40)->nullable();
            $table->string('status', 20)->default('queued'); // queued, sent, delivered, failed
            $table->unsignedTinyInteger('retry_count')->default(0);
            $table->string('provider_reference', 120)->nullable();
            $table->string('error_category', 60)->nullable();
            $table->timestamps();

            $table->index('notification_id');
            $table->index(['provider', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('notifications');
    }
};
