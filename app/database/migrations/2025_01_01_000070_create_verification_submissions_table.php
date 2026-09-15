<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Polymorphic verification workflow shared by clinics and suppliers.
     * verifiable_type: App\Modules\Clinic\Domain\Models\Clinic | App\Modules\Supplier\Domain\Models\Supplier
     */
    public function up(): void
    {
        Schema::create('verification_submissions', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('verifiable_type', 120);
            $table->unsignedBigInteger('verifiable_id');
            $table->string('status', 20)->default('draft');
            // draft, submitted, under_review, changes_requested, approved, rejected, suspended
            $table->text('reviewer_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['verifiable_type', 'verifiable_id']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('verification_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verification_submission_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 60);
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->timestamps();

            $table->index('verification_submission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
        Schema::dropIfExists('verification_submissions');
    }
};
