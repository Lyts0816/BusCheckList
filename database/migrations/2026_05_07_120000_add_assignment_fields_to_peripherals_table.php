<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peripherals', function (Blueprint $table): void {
            if (! Schema::hasColumn('peripherals', 'assigned_to')) {
                $table->string('assigned_to')->nullable()->after('description');
            }

            if (! Schema::hasColumn('peripherals', 'department_id')) {
                $table->foreignId('department_id')
                    ->nullable()
                    ->after('assigned_to')
                    ->constrained('departments')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    public function down(): void
    {
        Schema::table('peripherals', function (Blueprint $table): void {
            if (Schema::hasColumn('peripherals', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }

            if (Schema::hasColumn('peripherals', 'assigned_to')) {
                $table->dropColumn('assigned_to');
            }
        });
    }
};