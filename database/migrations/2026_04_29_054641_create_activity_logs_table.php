<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('performed_by');   // Admin/Staff name
            $table->string('role');           // Their role
            $table->string('action');         // What they did
            $table->string('target')->nullable(); // Who/what it was done to
            $table->string('icon')->default('fa-circle-info'); // FontAwesome icon
            $table->string('color')->default('blue'); // for styling
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};