<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_transfer_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('assigned_computer_id')->constrained('assigned_computers')->cascadeOnDelete();
            $table->string('previous_assigned_to');
            $table->string('new_assigned_to');
            $table->foreignId('previous_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('new_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->timestamp('transferred_at');
            $table->foreignId('performed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_transfer_histories');
    }
};
