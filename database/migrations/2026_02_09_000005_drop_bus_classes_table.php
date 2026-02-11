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
        Schema::dropIfExists('bus_classes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('bus_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_name');
            $table->string('description')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }
};
