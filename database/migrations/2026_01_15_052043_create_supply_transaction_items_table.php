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
        Schema::create('supply_transaction_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supply_transaction_id')
                ->constrained('supply_transactions')
                ->onDelete('cascade');

            $table->foreignId('supply_id')
                ->constrained('office_supplies')
                ->onDelete('cascade');

            $table->integer('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_transaction_items');
    }
};
