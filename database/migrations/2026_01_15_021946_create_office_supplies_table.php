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
        Schema::create('office_supplies', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);

            $table->string('category', 50)
                ->unique();

            $table->string('unit', 30)
                ->nullable();

            $table->integer('stock')
                ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_supplies');
    }
};
