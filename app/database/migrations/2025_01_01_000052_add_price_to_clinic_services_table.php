<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Each clinic sets its own price for a catalogue service (mockup P07 "Popular services"). */
    public function up(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->after('service_id');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_services', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
