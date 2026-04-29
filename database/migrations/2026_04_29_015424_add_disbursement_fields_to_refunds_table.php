<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->string('disbursement_status')->default('pending_disbursement')->after('status');
            $table->date('disbursement_date')->nullable()->after('disbursement_status');
        });
    }

    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn(['disbursement_status', 'disbursement_date']);
        });
    }
};