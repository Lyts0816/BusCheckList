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
        Schema::create('dispatch_trips_foreign', function (Blueprint $table) {
            $table->id();

            $table->string('trip_number', 100);

            $table->foreignId('routes_id')
                ->constrained('routes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('bus_number_id')
                ->constrained('bus_number')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('bus_class_id')
                ->constrained('bus_class')
                ->cascadeOnUpdate()
                ->restrictOnDelete();


            $table->foreignId('nature_of_trip_id')
                ->constrained('nature_of_trip')
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


            $table->dateTime('date_time_in_terminal');
            $table->dateTime('date_time_of_parking');
            $table->dateTime('date_time_of_departure');
            $table->dateTime('date_time_of_arrival');

            $table->time('idle_time_start');
            $table->time('idle_time_end');

            $table->integer('total_travel_time_minutes')->default(0);
            $table->integer('total_add_time_minutes')->default(0);
            $table->integer('km_run')->default(0);
            $table->integer('ticket_number')->default(0);
            $table->integer('passengers_on_board')->default(0);
            $table->integer('baggage_amount')->default(0);
            $table->integer('baggage_ticket_no')->default(0);

            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_trips_foreign');
    }
};
