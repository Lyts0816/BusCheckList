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
        Schema::create('turn_over', function (Blueprint $table) {
            $table->id();
            $table->string('from_department', 100)->nullable();
            $table->string('to_department', 100)->nullable();
            $table->date('current_date')->nullable();
            $table->date('printed_date')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('particulars', 100)->nullable() ;
            $table->string('serial_number', 100)->nullable();
            $table->string('recipient', 100)->nullable();
            $table->string('recipient_department_head', 100)->nullable();
            $table->string('endorser', 100)->nullable();
            $table->string('endorser_department_head', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turn_over');
    }
};
