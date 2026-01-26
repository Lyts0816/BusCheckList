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
        Schema::create('dispatch_trips', function (Blueprint $table) {
            $table->id();

            $table->string('trip_number', 100);

            $table->string('from', 20);

            $table->string('to', 20);

            $table->string('bus_number', 20);

            $table->string('bus_class', 20);

            $table->string('nature_of_trip', 50);

            $table->dateTime('date_time_in_terminal');

            $table->dateTime('date_time_of_parking');

            $table->dateTime('date_time_of_departure');

            $table->dateTime('date_time_of_arrival');

            $table->time('idle_time_start');
            $table->time('idle_time_end');

            $table->string('driver', 50);
            $table->string('conductor', 50);

            $table->integer('total_travel_time_minutes')->nullable()->default(0);

            $table->integer('total_add_time_minutes')->nullable()->default(0);

            $table->integer('km_run')->nullable()->default(0);

            $table->integer('ticket_number')->nullable()->default(0);

            $table->integer('passengers_on_board')->nullable()->default(0);

            $table->integer('baggage_amount')->nullable()->default(0);

            $table->integer('baggage_ticket_no')->nullable()->default(0);

            $table->string('remarks')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_trips');
    }
};
