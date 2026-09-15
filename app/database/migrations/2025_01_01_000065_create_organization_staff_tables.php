<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Explicit staff assignment to a clinic/supplier, used by policies to
     * scope access beyond the owning user (clinic_admin/clinic_staff,
     * supplier_admin/supplier_staff). Roles/permissions are still granted
     * via spatie (model_has_roles); this table answers "does this user
     * belong to this specific organization" for tenant-isolation checks.
     */
    public function up(): void
    {
        Schema::create('clinic_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 80)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['clinic_id', 'user_id']);
            $table->index('user_id');
        });

        Schema::create('supplier_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 80)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['supplier_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_staff');
        Schema::dropIfExists('clinic_staff');
    }
};
