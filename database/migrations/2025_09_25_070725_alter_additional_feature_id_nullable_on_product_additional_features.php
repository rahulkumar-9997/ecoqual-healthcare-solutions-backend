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
        Schema::table('product_additional_features', function (Blueprint $table) {
            $table->unsignedInteger('additional_feature_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_additional_features', function (Blueprint $table) {
            $table->unsignedInteger('additional_feature_id')->nullable(false)->change();
        });
    }
};
