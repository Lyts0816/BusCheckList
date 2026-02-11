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
        Schema::table('dispatch_sheets', function (Blueprint $table) {
            $table->foreignId('route_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete()
                ->index();


            // SNAPSHOT FIELDS
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->decimal('distance_at_dispatch', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispatch_sheets', function (Blueprint $table) {
            $table->dropForeign(['route_id']);

            $table->dropColumn([
                'route_id',
                'origin',
                'destination',
                'distance_at_dispatch',
            ]);
        });
    }
};
