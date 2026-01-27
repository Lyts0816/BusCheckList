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
        Schema::create('bus_numbers', function (Blueprint $table) {
            $table->id();

            $table->string('bus_number',20);
            $table->string('bus_model',50)->nullable();
            $table->string('bus_type',30)->nullable();
            $table->integer('seat_capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_numbers');
    }
};
