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
        Schema::create('borrow_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('borrow_id')
                ->constrained('borrow_logs')
                ->cascadeOnDelete();

            $table->string('item_name')->nullable();

            $table->string('item_asset_code')->nullable();

            $table->integer('quantity')->nullable();

            $table->date('return_date')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_items');
    }
};
