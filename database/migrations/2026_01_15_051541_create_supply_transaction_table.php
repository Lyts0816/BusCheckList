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
        Schema::create('supply_transactions', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['IN', 'OUT', 'ADJUSTMENT']);

            $table->string('department', 20);// just a drop-down string

            $table->string('user', 20);// just a text box
            
            $table->text('remarks')
            ->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_transactions');
    }
};
