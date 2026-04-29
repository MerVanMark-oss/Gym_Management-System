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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        
        // 1. Define the column type (Must match members.member_id)
        $table->unsignedBigInteger('member_id');
        
        // 2. Explicitly point to 'member_id' on the 'members' table
        $table->foreign('member_id')
              ->references('member_id')
              ->on('members')
              ->onDelete('cascade');

        $table->string('transaction_id')->unique();
        $table->decimal('amount', 10, 2);
        $table->string('type');
        $table->string('payment_method');
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

