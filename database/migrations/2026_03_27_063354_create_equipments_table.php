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
    Schema::create('equipments', function (Blueprint $table) {
        $table->id('equipment_id');
        $table->string('name');
        $table->string('category'); // e.g., Cardio, Strength
        $table->enum('status', ['good', 'under_repair', 'broken'])->default('good');
        $table->date('last_maintenance')->nullable();
        $table->date('next_maintenance')->nullable(); // Set to +15 days in Controller
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
