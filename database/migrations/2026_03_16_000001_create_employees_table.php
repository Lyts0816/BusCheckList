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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->nullable();
            $table->string('full_name');
            $table->string('department')->nullable();
            $table->string('remaining_vl')->nullable();
            $table->string('remaining_sl')->nullable();
            $table->string('availed_vl')->nullable();
            $table->string('availed_sl')->nullable();
            $table->string('availed_wo_pay')->nullable();
            $table->string('availed_sss_sl')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
