<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_care_requests', function (Blueprint $table) {
            $table->string('city_name')->nullable()->after('phone_number');
            $table->text('address')->nullable()->after('city_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_care_requests', function (Blueprint $table) {
            $table->dropColumn(['city_name', 'address']);
        });
    }
};
