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
        Schema::create('customer_care_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id');
            $table->string('category_name');
            $table->string('model_name')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone_number');
            $table->string('product_image')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_care_requests');
    }
};
