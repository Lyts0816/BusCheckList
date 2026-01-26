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
        Schema::create('bus_classes', function (Blueprint $table) {
            $table->id();

            $table->string('class_name',50);
            $table->text('description')->nullable();
            $table->string('remarks',100)->nullable();
            $table->timestamps();
        });
    }
    /**
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_class');
    }
};
