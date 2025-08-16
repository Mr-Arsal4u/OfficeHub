<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean up duplicate attendance entries
        // Keep only the most recent entry for each employee-date combination
        DB::statement("
            DELETE a1 FROM attendances a1
            INNER JOIN attendances a2 
            WHERE a1.id < a2.id 
            AND a1.employee_id = a2.employee_id 
            AND a1.date = a2.date
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed as it deletes data
    }
};
