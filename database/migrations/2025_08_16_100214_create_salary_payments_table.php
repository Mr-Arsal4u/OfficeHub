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
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salary_id');
            $table->foreign('salary_id')->references('id')->on('salaries')->onDelete('cascade');
            $table->unsignedBigInteger('loan_id')->nullable(); // If this payment includes loan repayment
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('set null');
            $table->decimal('amount', 10, 2); // Amount being paid
            $table->enum('payment_type', ['salary', 'loan_repayment', 'advance_payment'])->default('salary');
            $table->date('payment_date');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
