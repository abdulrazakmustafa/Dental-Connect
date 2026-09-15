<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('slug')->unique();
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();

            $table->string('verification_status', 20)->default('draft');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('region', 80)->nullable();
            $table->string('city', 80)->nullable();

            $table->timestamps();

            $table->index(['verification_status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
