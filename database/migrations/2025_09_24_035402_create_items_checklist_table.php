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
        Schema::create('items_checklist', function (Blueprint $table) {
            $table->id();
            $table->string('item_type', 50);
            $table->string('item_name', 50);
            $table->unsignedBigInteger('bus_id')->nullable();
            $table->string('item_asset_code', 100);
            $table->string('status', 20);
            $table->date('date_checked');
            $table->string('remarks', 255)->nullable();


            $table->foreign('bus_id')->references('id')->on('buses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_checklist');
    }
};
