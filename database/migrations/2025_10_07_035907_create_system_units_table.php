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
        Schema::create('system_units', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 50)->nullable();
            $table->string('serial_number', 50)->unique()->nullable();
            $table->string('model', 20)->nullable();
            $table->date('date_aquired')->nullable();
            $table->string('OS', 30)->nullable();
            $table->string('windows_serial_number', 50)->nullable();
            $table->string('microsoft_serial_number', 50)->nullable();
            $table->string('ram', 20)->nullable();
            $table->string('storage', 20)->nullable();
            $table->string('processor', 50)->nullable();
            $table->string('ip_address', 20)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_units');
    }
};
