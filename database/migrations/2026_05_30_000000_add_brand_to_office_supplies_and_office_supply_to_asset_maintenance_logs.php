<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('office_supplies', function (Blueprint $table): void {
            if (! Schema::hasColumn('office_supplies', 'brand')) {
                $table->string('brand')->nullable()->after('name');
            }
        });

        Schema::table('asset_maintenance_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('asset_maintenance_logs', 'office_supply_id')) {
                $table->foreignId('office_supply_id')
                    ->nullable()
                    ->after('component_id')
                    ->constrained('office_supplies')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    public function down(): void
    {
        Schema::table('asset_maintenance_logs', function (Blueprint $table): void {
            if (Schema::hasColumn('asset_maintenance_logs', 'office_supply_id')) {
                $table->dropForeign(['office_supply_id']);
                $table->dropColumn('office_supply_id');
            }
        });

        Schema::table('office_supplies', function (Blueprint $table): void {
            if (Schema::hasColumn('office_supplies', 'brand')) {
                $table->dropColumn('brand');
            }
        });
    }
};