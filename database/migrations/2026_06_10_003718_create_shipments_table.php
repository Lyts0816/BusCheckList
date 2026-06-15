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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            // Tracking
            $table->string('tracking_number')->unique();
            $table->string('barcode')->unique();
            $table->string('or_number')->nullable();

             // Route
            $table->string('origin_terminal')->nullable();
            $table->string('destination_terminal')->nullable();

            // Sender
            $table->string('sender_name');
            $table->text('sender_address')->nullable();
            $table->string('sender_contact')->nullable();

            // Recipient
            $table->string('recipient_name');
            $table->text('recipient_address')->nullable();
            $table->string('recipient_contact')->nullable();

             // Baggage Details
            $table->string('box_number')->nullable();
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('weight', 8, 2)->nullable();

            // Tracking Status
            $table->enum('status', [
                'created',
                'in_transit',
                'arrived',
                'claimed',
                'cancelled',
            ])->default('created');

             // Dates
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('claimed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
