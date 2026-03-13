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
        Schema::create('asset_maintenance_logs', function (Blueprint $table) {
            $table->id();

            // polymorphic relationship to asset
            $table->morphs('maintainable');

            // component involved
            $table->foreignId('component_id')
                ->nullable()
                ->constrained('components')
                ->nullOnDelete();

            $table->enum('maintenance_type', [
                'preventive',
                'repair',
                'upgrade',
                'replacement'
            ]);

            $table->date('maintenance_date');

            $table->string('performed_by')->nullable();

            $table->text('issue_reported')->nullable();

            $table->text('action_taken')->nullable();

            $table->decimal('cost', 10, 2)->nullable();

            $table->date('next_maintenance')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance_logs');
    }
};
