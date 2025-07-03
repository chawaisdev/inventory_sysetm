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
       Schema::create('sales', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('user_id'); // FK to users(id)
            $table->string('invoice_no', 50); // Invoice number
            $table->date('date'); // Sale date
            $table->decimal('total_amount', 10, 2); 
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('due_amount', 10, 2); 
            $table->string('payment_method', 50); 
            $table->text('note')->nullable(); 
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
