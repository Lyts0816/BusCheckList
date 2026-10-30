<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assigned_computers', function (Blueprint $table): void {
            $table->string('assignment_type')->default('computer_set')->after('department_id');
            $table->foreignId('peripheral_id')
                ->nullable()
                ->after('assignment_type')
                ->constrained('peripherals')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assigned_computers', function (Blueprint $table): void {
            $table->dropForeign(['peripheral_id']);
            $table->dropColumn(['assignment_type', 'peripheral_id']);
        });
    }
};
