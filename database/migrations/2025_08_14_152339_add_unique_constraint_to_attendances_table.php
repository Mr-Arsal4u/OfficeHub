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
        Schema::table('attendances', function (Blueprint $table) {
            // Add unique constraint to prevent duplicate attendance entries for same employee on same date
            $table->unique(['employee_id', 'date'], 'unique_employee_date_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Remove the unique constraint
            $table->dropUnique('unique_employee_date_attendance');
        });
    }
};
