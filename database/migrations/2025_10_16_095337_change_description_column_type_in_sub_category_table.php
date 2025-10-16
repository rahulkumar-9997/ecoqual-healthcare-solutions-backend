<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_category', function (Blueprint $table) {
            $table->text('description')->nullable()->change(); // change VARCHAR to TEXT
        });
    }

    public function down(): void
    {
        Schema::table('sub_category', function (Blueprint $table) {
            $table->string('description')->nullable()->change(); // rollback to VARCHAR
        });
    }
};
