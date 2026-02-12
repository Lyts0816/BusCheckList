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
        Schema::table('dispatched_trips', function (Blueprint $table) {
            $table->foreignId('dispatch_sheet_id')
                ->constrained('dispatch_sheets') // links to dispatch_sheets table
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispatched_trips', function (Blueprint $table) {
            $table->dropForeign(['dispatch_sheet_id']);
            $table->dropColumn('dispatch_sheet_id');
        });
    }
};
