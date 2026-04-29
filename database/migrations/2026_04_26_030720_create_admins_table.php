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
    Schema::create('admins', function (Blueprint $table) {
        $table->id('user_id'); // Primary Key, Auto-increment
        $table->string('username')->unique();
        $table->string('password');
        $table->string('contactnum');
        $table->string('email')->unique();
        $table->string('familyname');
        
        // Roles: super_admin, admin, staff
        $table->enum('role', ['super_admin', 'admin', 'staff'])->default('staff');
        
        // Status: active, inactive, suspended
        $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
        
        $table->dateTime('last_login')->nullable();

        $table->rememberToken();

        $table->timestamps(); // Handles date_created (created_at) and updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
