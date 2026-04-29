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
    Schema::create('refunds', function (Blueprint $table) {
        $table->id(); 
        
        // FK to Member (Linked to your custom member_id PK)
        $table->unsignedBigInteger('member_id');
        $table->foreign('member_id')
              ->references('member_id')
              ->on('members')
              ->onDelete('cascade');

        // FK to Membership Type
        $table->unsignedBigInteger('membership_type_id');
        $table->foreign('membership_type_id')
              ->references('id')
              ->on('membership_types')
              ->onDelete('cascade');

        $table->string('reason');
        $table->timestamps(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
