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
        Schema::create('assigned_computers', function (Blueprint $table) {
            $table->id();
            // System unit (required)
            $table->foreignId('system_unit_id')
                ->constrained('system_units')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Optional peripherals
            $table->foreignId('keyboard_id')
                ->nullable()
                ->constrained('peripherals')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('mouse_id')
                ->nullable()
                ->constrained('peripherals')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('monitor_id')
                ->nullable()
                ->constrained('peripherals')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('ups_id')
                ->nullable()
                ->constrained('peripherals')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Assignment info
            $table->string('assigned_to');
            $table->date('assigned_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_computers');
    }
};
