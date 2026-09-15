<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Marketplace is B2B (clinics <-> suppliers), authorized admin supervises.
     * Access is enforced server-side via the marketplace.access middleware/policy —
     * these tables carry no patient/guest visibility concept.
     */
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name', 120);
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['parent_id', 'is_active', 'sort_order']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();

            $table->string('name', 160);
            $table->string('slug');
            $table->string('brand', 120)->nullable();
            $table->string('reference_code', 80)->nullable();
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();

            $table->decimal('price', 12, 2)->nullable();
            $table->string('price_unit', 40)->nullable();
            $table->boolean('price_visible')->default(true);

            $table->string('status', 20)->default('draft'); // draft, active, inactive
            $table->string('moderation_status', 20)->default('pending'); // pending, approved, unpublished
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->index(['supplier_id', 'status', 'updated_at']);
            $table->index(['category_id', 'status', 'created_at']);
            $table->index(['status', 'moderation_status']);
            $table->unique(['supplier_id', 'slug']);
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();

            $table->unsignedInteger('quantity')->nullable();
            $table->text('message');
            $table->string('status', 20)->default('open'); // open, responded, closed, cancelled

            $table->timestamps();

            $table->index(['supplier_id', 'status', 'created_at']);
            $table->index(['clinic_id', 'status', 'created_at']);
        });

        Schema::create('rfq_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfq_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('status_change', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['rfq_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfq_messages');
        Schema::dropIfExists('rfqs');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
