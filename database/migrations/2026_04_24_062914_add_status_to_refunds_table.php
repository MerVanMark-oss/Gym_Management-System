<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('refunds', function (Blueprint $table) {
        // Add status column with a default value of 'N/A'
        $table->string('status')->default('N/A')->after('reason');
    });
}

public function down(): void
{
    Schema::table('refunds', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
