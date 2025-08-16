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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('users');
            $table->integer('month'); // 1-12
            $table->integer('year'); // 2024, 2025, etc.
            $table->decimal('base_amount', 10, 2); // Base salary amount
            $table->decimal('final_amount', 10, 2); // Final amount after deductions/additions
            $table->date('payment_date')->nullable(); // When salary was/will be paid
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('description')->nullable();
            $table->timestamps();
            
            // Ensure one salary record per employee per month per year
            $table->unique(['employee_id', 'month', 'year'], 'unique_employee_month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
