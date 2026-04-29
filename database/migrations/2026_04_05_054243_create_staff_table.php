<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            // This creates an auto-incrementing big integer PRIMARY KEY named 'staff_id'
            $table->id('staff_id'); 
            
            $table->string('name');
            $table->string('email')->unique();
            $table->string('contact');
            $table->string('role');  // Coach, Trainer, etc.
            $table->string('status')->default('active');
            $table->string('shift'); // Morning, Afternoon, Evening
            $table->date('hire_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};