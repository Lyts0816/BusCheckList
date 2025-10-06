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
        Schema::create('peripherals', function (Blueprint $table) {
            $table->id();
            $table->string('user', 50);
            $table->string('department', 50)->nullable();
            $table->string('item_type', 50);
            $table->string('model', 50);
            $table->string('serial_number', 50);
            $table->string('asset_code', 50)->unique();
            $table->date('date_acquired')->nullable();
            $table->string('status', 50)->default('active');
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peripherals');
    }
};
