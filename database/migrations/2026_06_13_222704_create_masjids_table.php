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
        Schema::create('masjids', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('area')->nullable();
            $table->string('city')->default('Karachi');
            $table->enum('sect', ['Sunni', 'Shia'])->default('Sunni');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('fajr')->nullable();
            $table->string('zuhr')->nullable();
            $table->string('asr')->nullable();
            $table->string('maghrib')->nullable();
            $table->string('isha')->nullable();
            $table->string('juma_time')->nullable();
            $table->string('eid_time')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masjids');
    }
};
