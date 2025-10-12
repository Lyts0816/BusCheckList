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
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('department', 30);
            $table->string('printer_host', 30);
            $table->string('printer_model', 50);
            $table->string('printer_asset_code', 50)->nullable();
            $table->string('printer_serial_number', 50)->unique();
            $table->date('date_aquired')->nullable();
            $table->date('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
