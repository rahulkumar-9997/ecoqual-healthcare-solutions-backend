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
            $table->string('invoice_image')->nullable()->after('product_image');
            $table->string('in_warranty')->nullable()->after('invoice_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_care_requests', function (Blueprint $table) {
            $table->dropColumn(['invoice_image', 'in_warranty']);
        });
    }
};
