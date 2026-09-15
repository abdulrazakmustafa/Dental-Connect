<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->ulid('public_id')->after('id')->unique();
            $table->string('phone', 20)->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('status', 20)->default('active')->after('password');
            $table->timestamp('suspended_at')->nullable()->after('status');
            $table->string('locale', 10)->default('en')->after('suspended_at');
            $table->string('timezone', 40)->default('Africa/Dar_es_Salaam')->after('locale');
            $table->timestamp('last_login_at')->nullable()->after('timezone');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn([
                'public_id', 'phone', 'phone_verified_at', 'status',
                'suspended_at', 'locale', 'timezone', 'last_login_at',
            ]);
        });
    }
};
