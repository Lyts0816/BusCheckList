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
        Schema::create('borrow_logs', function (Blueprint $table) {
            $table->id();
            $table->string('borrower_name')->nullable();
            $table->string('department')->nullable();
            $table->date('borrowed_date')->nullable();
            $table->string('purpose_borrowing')->nullable();
            $table->string('department_head_name')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_logs');
    }
};
