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
        Schema::create('borrow_logbook', function (Blueprint $table) {
            $table->id();
            $table->date('borrow_date');
            $table->string('borrower_name', 100);
            $table->string('department', 50);
            $table->string('equipment', 50);
            $table->string('item_asset_code', 50);
            $table->string('department_head_name', 50);
            $table->string('purpose_borrowing', 255);
            $table->string('handled_by');
            $table->date('date_returned');
            $table->string('remarks', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_logbook');
    }
};
