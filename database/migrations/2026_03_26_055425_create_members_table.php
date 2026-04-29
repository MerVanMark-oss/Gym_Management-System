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
    Schema::create('members', function (Blueprint $table) {
        $table->id('member_id'); // Primary Key
        $table->string('first_name');
        $table->string('last_name');
        $table->string('contact_number');
        
        // Use timestamps for precise check-in tracking
        $table->timestamp('join_date')->useCurrent();
        $table->timestamp('expiry_date')->nullable(); 
        
        // This links the member to the 'membership_types' table
        $table->foreignId('membership_type_id')->constrained('membership_types');

        // Status for your logic
        $table->enum('status', ['active', 'expired', 'cancelled', 'refunded'])->default('active');
        
        $table->timestamps(); // Track when record was created/updated
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
