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
        if (Schema::hasColumn('dispatched_trips', 'route_id')) {
            Schema::table('dispatched_trips', function (Blueprint $table) {
                $table->dropConstrainedForeignId('route_id');
            });
        }

        if (Schema::hasColumn('dispatched_trips', 'bus_number_id')) {
            Schema::table('dispatched_trips', function (Blueprint $table) {
                $table->dropConstrainedForeignId('bus_number_id');
            });
        }

        if (Schema::hasColumn('dispatched_trips', 'bus_class_id')) {
            Schema::table('dispatched_trips', function (Blueprint $table) {
                $table->dropConstrainedForeignId('bus_class_id');
            });
        }

        if (Schema::hasColumn('dispatched_trips', 'driver_id')) {
            Schema::table('dispatched_trips', function (Blueprint $table) {
                $table->dropConstrainedForeignId('driver_id');
            });
        }

        if (Schema::hasColumn('dispatched_trips', 'conductor_id')) {
            Schema::table('dispatched_trips', function (Blueprint $table) {
                $table->dropConstrainedForeignId('conductor_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispatched_trips', function (Blueprint $table) {
            $table->foreignId('route_id')
                ->constrained('routes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('bus_number_id')
                ->constrained('bus_numbers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('conductor_id')
                ->constrained('conductors')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
