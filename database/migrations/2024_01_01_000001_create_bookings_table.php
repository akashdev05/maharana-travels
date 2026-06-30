<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->integer('cab_id');
            $table->string('cab_name');
            $table->string('source');
            $table->string('destination');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->enum('trip_type', ['oneway', 'round-trip'])->default('oneway');
            $table->unsignedTinyInteger('passengers')->default(1);
            $table->string('name');
            $table->string('phone', 10);
            $table->string('email');
            $table->string('address')->nullable();
            $table->decimal('final_price', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
